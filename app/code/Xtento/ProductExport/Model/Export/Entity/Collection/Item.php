<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-09-09T09:53:31+00:00
 * File:          app/code/Xtento/ProductExport/Model/Export/Entity/Collection/Item.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Export\Entity\Collection;

class Item extends \Magento\Framework\DataObject
{
    public $collectionItem;
    public $collectionSize;
    public $currItemNo;

    public function __construct($collectionItem, $entityType, $currItemNo, $collectionCount)
    {
        parent::__construct();
        $this->collectionItem = $collectionItem;
        $this->collectionSize = $collectionCount;
        $this->currItemNo = $currItemNo;
        if ($entityType == \Xtento\ProductExport\Model\Export::ENTITY_PRODUCT) {
            $this->setProduct($collectionItem);
        }
        if ($entityType == \Xtento\ProductExport\Model\Export::ENTITY_REVIEW) {
            $this->setReview($collectionItem);
        }
        if ($entityType == \Xtento\ProductExport\Model\Export::ENTITY_CATEGORY) {
            $this->setCategory($collectionItem);
        }
    }

    public function getObject()
    {
        return $this->collectionItem;
    }
}