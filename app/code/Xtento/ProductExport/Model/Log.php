<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-14T15:37:35+00:00
 * File:          app/code/Xtento/ProductExport/Model/Log.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model;

/**
 * Class Log
 * Log model which keeps track of successful/failed export attempts
 * @package Xtento\ProductExport\Model
 */
class Log extends \Magento\Framework\Model\AbstractModel
{
    protected $resultMessages = [];

    // Log result types
    const RESULT_NORESULT = 0;
    const RESULT_SUCCESSFUL = 1;
    const RESULT_WARNING = 2;
    const RESULT_FAILED = 3;

    protected function _construct()
    {
        $this->_init('Xtento\ProductExport\Model\ResourceModel\Log');
        $this->_collectionName = 'Xtento\ProductExport\Model\ResourceModel\Log\Collection';
    }

    public function setResult($resultLevel)
    {
        if ($this->getResult() === NULL) {
            $this->setData('result', $resultLevel);
        } else if ($resultLevel > $this->getResult()) { // If result is failed, do not reset to warning for example.
            $this->setData('result', $resultLevel);
        }
    }

    public function addResultMessage($message)
    {
        array_push($this->resultMessages, $message);
    }

    public function getResultMessages()
    {
        if (empty($this->resultMessages)) {
            return false;
        }
        return (count($this->resultMessages) > 1) ? implode("\n", $this->resultMessages) : $this->resultMessages[0];
    }
}