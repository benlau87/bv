<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-17T14:35:35+00:00
 * File:          app/code/Xtento/ProductExport/Model/Export/Data/Category/General.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Export\Data\Category;

class General extends \Xtento\ProductExport\Model\Export\Data\AbstractData
{
    public function getConfiguration()
    {
        return [
            'name' => 'General category information',
            'category' => 'Category',
            'description' => 'Export extended category information.',
            'enabled' => true,
            'apply_to' => [\Xtento\ProductExport\Model\Export::ENTITY_CATEGORY],
        ];
    }

    // @codingStandardsIgnoreStart
    public function getExportData($entityType, $collectionItem)
    {
        // @codingStandardsIgnoreEnd
        // Set return array
        $returnArray = [];
        $this->writeArray = & $returnArray; // Write directly on category level
        // Fetch fields to export
        $category = $collectionItem->getCategory();

        // Timestamps of creation/update
        if ($this->fieldLoadingRequired('created_at_timestamp')) $this->writeValue('created_at_timestamp', $this->dateHelper->convertDateToStoreTimestamp($category->getCreatedAt()));
        if ($this->fieldLoadingRequired('updated_at_timestamp')) $this->writeValue('updated_at_timestamp', $this->dateHelper->convertDateToStoreTimestamp($category->getUpdatedAt()));

        // Which line is this?
        $this->writeValue('line_number', $collectionItem->currItemNo);
        $this->writeValue('count', $collectionItem->collectionSize);

        // Export information
        $this->writeValue('export_id', $this->_registry->registry('productexport_log') ? $this->_registry->registry('productexport_log')->getId() : 0);

        $this->exportCategoryData($category);

        // Done
        return $returnArray;
    }

    /**
     * @param $category \Magento\Catalog\Model\Category
     */
    protected function exportCategoryData($category)
    {
        $storeId = $this->getStoreId();
        if ($storeId) {
            $category->setStoreId($storeId);
            $this->writeValue('store_id', $storeId);
        } else {
            $this->writeValue('store_id', 0);
        }
        foreach ($category->getData() as $key => $value) {
            if ($key == 'entity_id') {
                continue;
            }
            if (!$this->fieldLoadingRequired($key)) {
                continue;
            }
            $attribute = $category->getResource()->getAttribute($key);
            $attrText = '';
            if ($attribute) {
                try {
                    $attrText = $category->getAttributeText($key);
                } catch (\Exception $e) {
                    //echo "Problem with attribute $key: ".$e->getMessage();
                    continue;
                }
            }
            if (!empty($attrText)) {
                $this->writeValue($key, $attrText);
            } else {
                $this->writeValue($key, $value);
            }
        }

        // Extended fields
        if ($this->fieldLoadingRequired('category_url')) {
            if ($storeId) {
                $category->setStoreId($storeId);
            }
            $this->writeValue('category_url', $category->getUrl());
        }
    }
}