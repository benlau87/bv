<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-14T15:37:57+00:00
 * File:          app/code/Xtento/ProductExport/Model/Destination/Local.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Destination;

class Local extends AbstractClass
{
    public function testConnection()
    {
        $exportDirectory = $this->fixBasePath($this->getDestination()->getPath());
        $testResult = new \Magento\Framework\DataObject();

        // Check for forbidden folders
        $forbiddenFolders = [
            $this->filesystem->getDirectoryRead(
                \Magento\Framework\App\Filesystem\DirectoryList::ROOT
            )->getAbsolutePath()
        ];
        foreach ($forbiddenFolders as $forbiddenFolder) {
            if (realpath($exportDirectory) == $forbiddenFolder) {
                return $testResult->setSuccess(false)->setMessage(
                    __(
                        'It is not allowed to save export files in the directory you have specified. Please change the local export directory to be located in the ./var/ folder for example. Do not use the Magento root directory for example.'
                    )
                );
            }
        }

        if (!is_dir($exportDirectory) && !preg_match('/%exportid%/', $exportDirectory)) {
            // Try to create the directory.
            if (!@mkdir($exportDirectory)) {
                return $testResult->setSuccess(false)->setMessage(
                    __(
                        'The specified local directory does not exist. We could not create it either. Please make sure the parent directory is writable or create the directory manually: %1',
                        $exportDirectory
                    )
                );
            } else {
                $testResult->setDirectoryCreated(true);
            }
        }
        $this->connection = @opendir($exportDirectory);
        if (!$this->connection || @!is_writable($exportDirectory)) {
            return $testResult->setSuccess(false)->setMessage(
                __(
                    'Could not open local export directory for writing. Please make sure that we have rights to read and write in the directory: %1',
                    $exportDirectory
                )
            );
        }
        if ($testResult->getDirectoryCreated()) {
            $testResult->setSuccess(true)->setMessage(
                __('Local directory didn\'t exist and was created successfully. Connection tested successfully.')
            );
            if (!$this->getDestination()->getBackupDestination()) {
                $this->getDestination()->setLastResult($testResult->getSuccess())->setLastResultMessage(
                    $testResult->getMessage()
                )->save();
            }
            return $testResult;
        } else {
            $testResult->setSuccess(true)->setMessage(
                __('Local directory exists and is writable. Connection tested successfully.')
            );
            if (!$this->getDestination()->getBackupDestination()) {
                $this->getDestination()->setLastResult($testResult->getSuccess())->setLastResultMessage(
                    $testResult->getMessage()
                )->save();
            }
            return $testResult;
        }
    }

    public function saveFiles($fileArray)
    {
        if (empty($fileArray)) {
            return [];
        }
        $savedFiles = [];
        $logEntry = $this->_registry->registry('productexport_log');
        // Test connection
        $testResult = $this->testConnection();
        if (!$testResult->getSuccess()) {
            $logEntry->setResult(\Xtento\ProductExport\Model\Log::RESULT_WARNING);
            $logEntry->addResultMessage(
                __(
                    'Destination "%1" (ID: %2): %3',
                    $this->getDestination()->getName(),
                    $this->getDestination()->getId(),
                    $testResult->getMessage()
                )
            );
            if (!$this->getDestination()->getBackupDestination()) {
                $this->getDestination()->setLastResultMessage($testResult->getMessage());
            }
            return false;
        }
        // Save files
        $exportDirectory = $this->fixBasePath($this->getDestination()->getPath());
        foreach ($fileArray as $filename => $data) {
            $originalFilename = $filename;
            if ($this->getDestination()->getBackupDestination()) {
                // Add the export_id as prefix to uniquely store files in the backup/copy folder
                $filename = $logEntry->getId() . '_' . $filename;
            }
            if (preg_match('/%exportid%/', $exportDirectory)) {
                if ($this->_registry->registry('productexport_log')) {
                    $exportId = $this->_registry->registry('productexport_log')->getId();
                } else {
                    $exportId = 0;
                }
                $exportDirectory = preg_replace('/%exportid%/', $exportId, $exportDirectory);
                if (!is_dir($exportDirectory)) {
                    @mkdir($exportDirectory);
                }
            }
            if (!@file_put_contents($exportDirectory . $filename, $data) && !empty($data)) {
                $logEntry->setResult(\Xtento\ProductExport\Model\Log::RESULT_WARNING);
                $message = __(
                    "Could not save file %1 in directory %2. Please make sure the directory is writable.",
                    $filename,
                    $exportDirectory
                );
                $logEntry->addResultMessage(
                    __(
                        'Destination "%1" (ID: %2): %3',
                        $this->getDestination()->getName(),
                        $this->getDestination()->getId(),
                        $message
                    )
                );
                if (!$this->getDestination()->getBackupDestination()) {
                    $this->getDestination()->setLastResultMessage(__($message));
                }
            } else {
                $savedFiles[] = $exportDirectory . $originalFilename;
            }
        }
        return $savedFiles;
    }

    protected function fixBasePath($originalPath)
    {
        /*
        * Let's try to fix the import directory and replace the dot with the actual Magento root directory.
        * Why? Because if the cronjob is executed using the PHP binary a different working directory (when using a dot (.) in a directory path) could be used.
        * But Magento is able to return the right base path, so let's use it instead of the dot.
        */
        $originalPath = str_replace('/', DIRECTORY_SEPARATOR, $originalPath);
        if (substr($originalPath, 0, 2) == '.' . DIRECTORY_SEPARATOR) {
            return rtrim($this->filesystem->getDirectoryRead(
                \Magento\Framework\App\Filesystem\DirectoryList::ROOT
            )->getAbsolutePath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . substr($originalPath, 2);
        }
        return $originalPath;
    }
}