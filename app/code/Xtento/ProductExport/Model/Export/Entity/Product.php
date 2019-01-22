<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2018-08-29T12:39:02+00:00
 * File:          app/code/Xtento/ProductExport/Model/Export/Entity/Product.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Export\Entity;

use Magento\Framework\Exception\LocalizedException;

class Product extends AbstractEntity
{
    protected $entityType = \Xtento\ProductExport\Model\Export::ENTITY_PRODUCT;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Magento\CatalogInventory\Helper\Stock
     */
    protected $stockHelper;

    /**
     * Product constructor.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Xtento\ProductExport\Model\ProfileFactory $profileFactory
     * @param \Xtento\ProductExport\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory
     * @param \Xtento\ProductExport\Model\Export\Data $exportData
     * @param \Magento\Store\Model\StoreFactory $storeFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     * @param \Magento\CatalogInventory\Helper\Stock $stockHelper
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Xtento\ProductExport\Model\ProfileFactory $profileFactory,
        \Xtento\ProductExport\Model\ResourceModel\History\CollectionFactory $historyCollectionFactory,
        \Xtento\ProductExport\Model\Export\Data $exportData,
        \Magento\Store\Model\StoreFactory $storeFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\CatalogInventory\Helper\Stock $stockHelper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        $this->resourceConnection = $resourceConnection;
        $this->stockHelper = $stockHelper;
        parent::__construct($context, $registry, $profileFactory, $historyCollectionFactory, $exportData, $storeFactory, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $collection = $this->productCollectionFactory->create();
        //    ->addAttributeToSelect('*');
        $collection->addTaxPercents();

        $this->collection = $collection;
        parent::_construct();
    }

    public function runExport($forcedCollectionItem = false)
    {
        if ($this->getProfile()) {
            if ($this->getProfile()->getStoreId()) {
                //$this->storeManager->setCurrentStore($this->getProfile()->getStoreId());  // fixes catalog price rules
                $store = $this->storeManager->getStore($this->getProfile()->getStoreId());
                if ($store->getId()) {
                    $websiteId = $store->getWebsiteId();
                } else {
                    throw new LocalizedException(__('Product export failed. The specified store_id %1 does not exist anymore. Please update the profile in the Stores & Filters tab and select a valid store view.', $this->getProfile()->getStoreId()));
                }
                $this->collection->getSelect()->joinLeft(
                    $this->resourceConnection->getTableName('catalog_product_index_price') . ' AS price_index',
                    'price_index.entity_id=e.entity_id AND customer_group_id=0 AND price_index.website_id=' . $websiteId,
                    [
                        'min_price' => 'min_price',
                        'max_price' => 'max_price',
                        'tier_price' => 'tier_price',
                        'final_price' => 'final_price'
                    ]
                );
                $this->collection->addStoreFilter($this->getProfile()->getStoreId());
                $this->collection->setStore($this->getProfile()->getStoreId());
                $this->collection->/*setStore($this->getProfile()->getStoreId())->addWebsiteFilter(Mage::app()->getStore($this->getProfile()->getStoreId())->getWebsiteId())->*/
                    addAttributeToSelect("tax_class_id");
            }
            /** Add product reviews */
            /*
            $this->collection->getSelect()->joinLeft(
                $this->resourceConnection->getTableName('review_entity_summary') . ' AS reviews',
                'reviews.entity_pk_value=e.entity_id AND customer_group_id=0 AND reviews.store_id=' . $store->getId(),
                [
                    'reviews_count' => 'reviews_count',
                    'rating_summary' => 'rating_summary'
                ]
            );
            */
            if ($this->getProfile()->getOutputType() == 'csv' || $this->getProfile()->getOutputType() == 'xml') {
                // Fetch all fields
                $this->collection->addAttributeToSelect('*');
            } else {
                $attributesToSelect = explode(",", $this->getProfile()->getAttributesToSelect());
                if (empty($attributesToSelect) || (isset($attributesToSelect[0]) && empty($attributesToSelect[0]))) {
                    $attributes = '*';
                } else {
                    // Get all attributes which should be always fetched
                    $attributes = ['entity_id', 'sku', 'price', 'name', 'status', 'url_key', 'type_id', 'image'];
                    $attributes = array_merge($attributes, $attributesToSelect);
                    $attributes = array_unique($attributes);
                }
                $this->collection->addAttributeToSelect($attributes);
            }
            #echo($this->collection->getSelect());
            if ($this->getProfile()->getExportFilterProductVisibility() != '') {
                $this->collection->addAttributeToFilter(
                    'visibility',
                    ['in' => explode(",", $this->getProfile()->getExportFilterProductVisibility())]
                );
            }
            if ($this->getProfile()->getExportFilterProductStatus() != '') {
                $this->collection->addAttributeToFilter(
                    'status',
                    ['in' => explode(",", $this->getProfile()->getExportFilterProductStatus())]
                );
            }
            if ($this->getProfile()->getExportFilterInstockOnly() === "1") {
                $this->stockHelper->addInStockFilterToCollection($this->collection);
            }
        }
        return parent::runExport($forcedCollectionItem);
    }


    protected function _runExport($forcedCollectionItem = false)
    {
        $hiddenProductTypes = explode(",", $this->getProfile()->getExportFilterProductType());
        if (!empty($hiddenProductTypes)) {
            $this->collection->addAttributeToFilter('type_id', ['nin' => $hiddenProductTypes]);
        }
        return parent::_runExport($forcedCollectionItem);
    }
}