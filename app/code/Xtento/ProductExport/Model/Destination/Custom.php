<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-14T15:37:35+00:00
 * File:          app/code/Xtento/ProductExport/Model/Destination/Custom.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Destination;

class Custom extends AbstractClass
{
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
        if (!@$this->objectManager->create($this->getDestination()->getCustomClass())) {
            $this->getTestResult()->setSuccess(false)->setMessage(__('Custom class NOT found.'));
        } else {
            $this->getTestResult()->setSuccess(true)->setMessage(__('Custom class found and ready to use.'));
        }
        return true;
    }

    public function saveFiles($fileArray)
    {
        if (empty($fileArray)) {
            return [];
        }
        // Init connection
        $this->initConnection();
        // Call custom class
        @$this->objectManager->create($this->getDestination()->getCustomClass())->saveFiles($fileArray);
        return array_keys($fileArray);
    }
}