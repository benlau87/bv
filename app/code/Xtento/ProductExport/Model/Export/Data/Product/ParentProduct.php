<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2018-09-10T19:44:31+00:00
 * File:          app/code/Xtento/ProductExport/Model/Export/Data/Product/ParentProduct.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Export\Data\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\DataObject;
use Xtento\ProductExport\Model\Export;

class ParentProduct extends General
{
    /**
     * Parent product cache
     */
    protected static $parentProductCache = [];

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Categories
     */
    protected $categoriesSingleton;

    /**
     * @var \Magento\GroupedProduct\Model\Product\Type\Grouped
     */
    protected $productTypeGrouped;

    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $productTypeConfigurable;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * ParentProduct constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Xtento\XtCore\Helper\Date $dateHelper
     * @param \Xtento\XtCore\Helper\Utils $utilsHelper
     * @param \Magento\Tax\Model\Config $taxConfig
     * @param \Magento\Catalog\Model\ResourceModel\Product $resourceProduct
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Eav\Model\Entity\Attribute\SetFactory $attributeSetFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Tax\Model\Calculation $taxCalculation
     * @param \Magento\Framework\App\ProductMetadata $productMetadata
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param Categories $categoriesSingleton
     * @param \Magento\GroupedProduct\Model\Product\Type\Grouped $productTypeGrouped
     * @param \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $productTypeConfigurable
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
        \Magento\Tax\Model\Config $taxConfig,
        \Magento\Catalog\Model\ResourceModel\Product $resourceProduct,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Eav\Model\Entity\Attribute\SetFactory $attributeSetFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        ProductRepositoryInterface $productRepository,
        \Magento\Tax\Model\Calculation $taxCalculation,
        \Magento\Framework\App\ProductMetadata $productMetadata,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Helper\Image $imageHelper,
        Categories $categoriesSingleton,
        \Magento\GroupedProduct\Model\Product\Type\Grouped $productTypeGrouped,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $productTypeConfigurable,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $dateHelper,
            $utilsHelper,
            $taxConfig,
            $resourceProduct,
            $storeManager,
            $attributeSetFactory,
            $localeDate,
            $productRepository,
            $taxCalculation,
            $productMetadata,
            $objectManager,
            $imageHelper,
            $resource,
            $resourceCollection,
            $data
        );
        $this->productRepository = $productRepository;
        $this->categoriesSingleton = $categoriesSingleton;
        $this->productTypeGrouped = $productTypeGrouped;
        $this->productTypeConfigurable = $productTypeConfigurable;
        $this->resourceConnection = $resourceConnection;
    }

    public function getConfiguration()
    {
        return [
            'name' => 'Parent item information',
            'category' => 'Product',
            'description' => 'Export parent item',
            'enabled' => true,
            'apply_to' => [\Xtento\ProductExport\Model\Export::ENTITY_PRODUCT],
        ];
    }

    /**
     * @param $entityType
     * @param $collectionItem
     *
     * @return array
     */
    // @codingStandardsIgnoreStart
    public function getExportData($entityType, $collectionItem)
    {
        // @codingStandardsIgnoreStart
        // Set return array
        $returnArray = [];

        // Fetch product - should be a child
        $product = $collectionItem->getProduct();

        if ($this->getProfile()->getOutputType() == 'xml') {
            return $returnArray;
        }

        $parentId = -1;
        // Check if it's a child product, and if yes, find & export parent id
        if ($this->fieldLoadingRequired('parent_id')) {
            $this->writeArray = & $returnArray; // Write on product level
            $parentId = $this->getFirstParentProductId($product);
            $this->writeValue('parent_id', $parentId);
        }

        // Find & export parent item
        if ($this->fieldLoadingRequired('parent_item') || $this->fieldLoadingRequired('option_parameters_in_url')) {
            $returnArray['parent_item'] = $this->getParentData($product, $parentId);
            $this->writeArray = & $returnArray; // Write on product level
        }

        // Done
        return $returnArray;
    }


    /**
     * Get the parent data as array
     * If the parent has also a parent, its data is exported as well
     * This function changes the $writeArray reference
     * 
     * @param \Magento\Catalog\Model\Product $product
     * @param int $parentId [optional = -1]
     * @return array
     */
    protected function getParentData($product, $parentId = -1, $depth = 0)
    {
        $data = [];

        // Load parent id
        if ($parentId == -1) {
            $parentId = $this->getFirstParentProductId($product);
        }
        if (!$parentId)
            return $data;

        // Check cache
        if (!isset(self::$parentProductCache[$this->getStoreId()])) {
            self::$parentProductCache[$this->getStoreId()] = [];
        }

        if (array_key_exists($parentId, self::$parentProductCache[$this->getStoreId()]) && !$this->fieldLoadingRequired('option_parameters_in_url')) {
            return self::$parentProductCache[$this->getStoreId()][$parentId];
        }

        // Load parent
        /** @var \Magento\Catalog\Model\Product $parent */
        try {
            if ($this->getStoreId()) {
                $parent = $this->productRepository->getById($parentId, false, $this->getStoreId());
            } else {
                $parent = $this->productRepository->getById($parentId);
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $parent = false;
        }
        if ($parent && $parent->getId()) {
            $this->writeArray = & $data; // Write on parent_item level

            if ($this->fieldLoadingRequired('option_parameters_in_url') && $parent->getTypeId() === \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE ) {
                $superAttributesWithValues = [];
                $superAttributes = $parent->getTypeInstance()->getConfigurableAttributes($parent);
                foreach ($superAttributes as $superAttribute) {
                    $superAttributeId = $superAttribute->getProductAttribute()->getId();
                    $superAttributeCode = $superAttribute->getProductAttribute()->getAttributeCode();
                    $superAttributeValues = $superAttribute->getOptions() ? $superAttribute->getOptions() : [];
                    foreach ($superAttributeValues as $superAttributeValue) {
                        if ($superAttributeValue['value_index'] == $product->getData($superAttributeCode)) {
                            $superAttributesWithValues[] = $superAttributeId . "=" . $superAttributeValue['value_index'];
                        }
                    }
                }
                $this->writeValue('option_parameters_in_url', implode("&", $superAttributesWithValues));
            }
            // Export product data of parent product
            $this->exportProductData($parent, $data);
            $this->writeValue('entity_id', $parent->getId());
            if ($this->fieldLoadingRequired('parent_item/cats')) {
                // Export categories for parent product
                $fakedCollectionItem = new DataObject();
                $fakedCollectionItem->setProduct($parent);
                $exportClass = $this->categoriesSingleton;
                $exportClass->setProfile($this->getProfile());
                $exportClass->setShowEmptyFields($this->getShowEmptyFields());
                $returnData = $exportClass->getExportData(Export::ENTITY_PRODUCT, $fakedCollectionItem);
                if (is_array($returnData) && !empty($returnData)) {
                    $this->writeArray = array_merge_recursive($this->writeArray, $returnData);
                }
            }

            // Parent's parent
            $grandParentId = $this->getFirstParentProductId($parent);
            if ($grandParentId && $grandParentId != $parent->getId() && $depth < 5) { // Maximum 5 parent products to avoid recursion
                $depth++;
                $data['parent_item'] = $this->getParentData($parent, $grandParentId, $depth);
            }
        }

        // Cache parent product
        self::$parentProductCache[$this->getStoreId()][$parentId] = $data;

        return $data;
    }

    /**
     * Get parent id of the product
     * @param \Magento\Catalog\Model\Product $product
     * @return int
     */
    protected function getFirstParentProductId($product)
    {
        $parentId = null;
        #if ($product->getTypeId() == 'simple') {
            $parentIds = $this->productTypeGrouped->getParentIdsByChild($product->getId());
            if (!$parentIds) {
                $parentIds = $this->productTypeConfigurable->getParentIdsByChild($product->getId());
            }
            foreach ($parentIds as $possibleParentId) {
                // Check if parent product exists, if yes return first existing parent product
                $readAdapter = $this->resourceConnection->getConnection();
                $select = $readAdapter->select()
                    ->from($this->resourceConnection->getTableName('catalog_product_entity'), ['entity_id'])
                    ->where("entity_id = ?", $possibleParentId);
                $products = $readAdapter->fetchAll($select);
                if (count($products) > 0) {
                    $parentId = $possibleParentId;
                }
            }
        #}

        return (int)$parentId;
    }
}