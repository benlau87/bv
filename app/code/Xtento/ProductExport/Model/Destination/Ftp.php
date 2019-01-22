<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-14T15:37:57+00:00
 * File:          app/code/Xtento/ProductExport/Model/Destination/Ftp.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Destination;

class Ftp extends AbstractClass
{
    const TYPE_FTP = 'ftp';
    const TYPE_FTPS = 'ftps';

    public function testConnection()
    {
        $this->initConnection();
        if (!$this->getDestination()->getBackupDestination()) {
            $this->getDestination()->setLastResult($this->getTestResult()->getSuccess())->setLastResultMessage($this->getTestResult()->getMessage())->save();
        }
        return $this->getTestResult();
    }

    public function initConnection()
    {
        $this->setDestination($this->destinationFactory->create()->load($this->getDestination()->getId()));
        $testResult = new \Magento\Framework\DataObject();
        $this->setTestResult($testResult);

        if ($this->getDestination()->getFtpType() == self::TYPE_FTPS) {
            if (function_exists('ftp_ssl_connect')) {
                $this->connection = @ftp_ssl_connect($this->getDestination()->getHostname(), $this->getDestination()->getPort(), $this->getDestination()->getTimeout());
            } else {
                $this->getTestResult()->setSuccess(false)->setMessage(__('No FTP-SSL functions found. Please compile PHP with SSL support.'));
                return false;
            }
        } else {
            if (function_exists('ftp_connect')) {
                $this->connection = @ftp_connect($this->getDestination()->getHostname(), $this->getDestination()->getPort(), $this->getDestination()->getTimeout());
            } else {
                $this->getTestResult()->setSuccess(false)->setMessage(__('No FTP functions found. Please compile PHP with FTP support.'));
                return false;
            }
        }

        if (!$this->connection) {
            $this->getTestResult()->setSuccess(false)->setMessage(__('Could not connect to FTP server. Please make sure that there is no firewall blocking the outgoing connection to the FTP server and that the timeout is set to a high enough value. If this error keeps occurring, please get in touch with your server hoster / server administrator AND with the server hoster / server administrator of the remote FTP server. A firewall is probably blocking ingoing/outgoing FTP connections.'));
            return false;
        }

        if (!@ftp_login($this->connection, $this->getDestination()->getUsername(), $this->encryptor->decrypt($this->getDestination()->getPassword()))) {
            $this->getTestResult()->setSuccess(false)->setMessage(__('Could not log into FTP server. Wrong username or password.'));
            return false;
        }

        if ($this->getDestination()->getFtpPasv()) {
            // Enable passive mode
            if (!@ftp_pasv($this->connection, true)) {
                #$this->getTestResult()->setSuccess(false)->setMessage(__('Could not enable passive mode for FTP connection.'));
                #$this->getDestination()->setLastResult($this->getTestResult()->getSuccess())->setLastResultMessage($this->getTestResult()->getMessage())->save();
                #return false;
            }
        }

        if (!@ftp_chdir($this->connection, $this->getDestination()->getPath())) {
            $this->getTestResult()->setSuccess(false)->setMessage(__('Could not change directory on FTP server to export directory. Please make sure the directory exists (base path must be exactly the same) and that we have rights to read in the directory.'));
            return false;
        }

        $this->getTestResult()->setSuccess(true)->setMessage(__('Connection with FTP server tested successfully.'));
        return true;
    }


    public function saveFiles($fileArray)
    {
        if (empty($fileArray)) {
            return [];
        }
        $savedFiles = [];
        $logEntry = $this->_registry->registry('productexport_log');
        // Test & init connection
        $this->initConnection();
        if (!$this->getTestResult()->getSuccess()) {
            $logEntry->setResult(\Xtento\ProductExport\Model\Log::RESULT_WARNING);
            $logEntry->addResultMessage(__('Destination "%1" (ID: %2): %3', $this->getDestination()->getName(), $this->getDestination()->getId(), $this->getTestResult()->getMessage()));
            return false;
        }

        // Save files
        foreach ($fileArray as $filename => $data) {
            $originalFilename = $filename;
            if ($this->getDestination()->getBackupDestination()) {
                // Add the export_id as prefix to uniquely store files in the backup/copy folder
                $filename = $logEntry->getId() . '_' . $filename;
            }
            $tempHandle = fopen('php://temp', 'r+');
            fwrite($tempHandle, $data);
            rewind($tempHandle);
            if (!@ftp_fput($this->connection, $filename, $tempHandle, FTP_BINARY)) {
                $logEntry->setResult(\Xtento\ProductExport\Model\Log::RESULT_WARNING);
                $message = sprintf("Could not save file %1 in directory %2 on FTP server %3. You can try enabling passive mode in the configuration. Please make sure the directory is writable. Also please make sure that there is no firewall blocking the outgoing connection to the FTP server. If this error keeps occurring, please get in touch with your server hoster / server administrator AND with the server hoster / server administrator of the remote FTP server, so they can adjust the firewall.", $filename, $this->getDestination()->getPath(), $this->getDestination()->getHostname());
                $logEntry->addResultMessage(__('Destination "%1" (ID: %2): %3', $this->getDestination()->getName(), $this->getDestination()->getId(), $message));
                if (!$this->getDestination()->getBackupDestination()) {
                    $this->getDestination()->setLastResultMessage(__($message));
                }
            } else {
                $savedFiles[] = $this->getDestination()->getPath() . $originalFilename;
            }
        }
        @ftp_close($this->connection);
        return $savedFiles;
    }
}