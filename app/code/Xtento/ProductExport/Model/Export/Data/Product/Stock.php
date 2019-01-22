<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-18T18:18:21+00:00
 * File:          app/code/Xtento/ProductExport/Model/Export/Data/Product/Stock.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Export\Data\Product;

class Stock extends \Xtento\ProductExport\Model\Export\Data\AbstractData
{
    protected static $stockIdCache = [];

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $stockRegistry;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * Stock constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Xtento\XtCore\Helper\Date $dateHelper
     * @param \Xtento\XtCore\Helper\Utils $utilsHelper
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Xtento\XtCore\Helper\Date $dateHelper,
        \Xtento\XtCore\Helper\Utils $utilsHelper,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $dateHelper, $utilsHelper, $resource, $resourceCollection, $data);
        $this->stockRegistry = $stockRegistry;
        $this->resourceConnection = $resourceConnection;
    }

    public function getConfiguration()
    {
        return [
            'name' => 'Stock information',
            'category' => 'Product',
            'description' => 'Export stock information such as qty on stock.',
            'enabled' => true,
            'apply_to' => [\Xtento\ProductExport\Model\Export::ENTITY_PRODUCT],
        ];
    }

    // @codingStandardsIgnoreStart
    public function getExportData($entityType, $collectionItem)
    {
        // @codingStandardsIgnoreEnd
        // Set return array
        $returnArray = [];
        $this->writeArray = & $returnArray; // Write directly on product level
        // Fetch fields to export
        $product = $collectionItem->getProduct();

        $exportAllFields = false;
        if ($this->getProfile()->getOutputType() == 'xml') {
            $exportAllFields = true;
        }

        if ($this->fieldLoadingRequired('stock') && !$exportAllFields) {
            $returnArray['stock'] = [];
            $this->writeArray = & $returnArray['stock'];

            $stockItem = $this->stockRegistry->getStockItem($product->getId(), $this->getStoreId());
            if ($stockItem->getId()) {
                foreach ($stockItem->getData() as $key => $value) {
                    if (!$this->fieldLoadingRequired($key)) {
                        continue;
                    }
                    if ($key == 'qty') {
                        $value = sprintf('%d', $value);
                    }
                    $this->writeValue($key, $value);
                }
            }

            $this->writeArray = & $returnArray; // Write on product level
        }

        // Fetch stock for different stock_ids
        if (($this->fieldLoadingRequired('stock_ids') || $this->fieldLoadingRequired('total_stock')) && !$exportAllFields) {
            if (!isset(self::$stockIdCache[$product->getId()])) {
                $select = $this->resourceConnection->getConnection()->select()
                    ->from($this->resourceConnection->getTableName('cataloginventory_stock_item'), ['product_id', 'stock_id', 'qty']
                    )
                    ->where('product_id = ?', $product->getId());
                $stockItems = $this->resourceConnection->getConnection()->fetchAll($select);

                foreach ($stockItems as $stockItem) {
                    self::$stockIdCache[$stockItem['product_id']][$stockItem['stock_id']] = $stockItem['qty'];
                }
            }
            $totalStockQty = 0;
            $returnArray['stock_ids'] = [];
            if (isset(self::$stockIdCache[$product->getId()])) {
                foreach (self::$stockIdCache[$product->getId()] as $stockId => $qty) {
                    if ($stockId > 0) {
                        $this->writeArray = & $returnArray['stock_ids'][];
                        $this->writeValue('stock_id', $stockId);
                        $this->writeValue('qty', $qty);
                        $totalStockQty += $qty;
                    }
                }
            }
            $this->writeArray = & $returnArray; // Write on product level
            $this->writeValue('total_stock_qty', $totalStockQty);
        }

        // Done
        return $returnArray;
    }
}