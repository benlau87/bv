<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2018-09-10T19:58:38+00:00
 * File:          app/code/Xtento/ProductExport/Model/Export/Data/Product/General.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Export\Data\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;

class General extends \Xtento\ProductExport\Model\Export\Data\AbstractData
{
    /**
     * Cache
     */
    protected static $attributeSetCache = [];
    protected static $mediaGalleryBackend = false;

    /**
     * @var \Magento\Tax\Model\Config
     */
    protected $taxConfig;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $resourceProduct;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Eav\Model\Entity\Attribute\SetFactory
     */
    protected $attributeSetFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $localeDate;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Tax\Model\Calculation
     */
    protected $taxCalculation;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\App\ProductMetadata
     */
    protected $productMetadata;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * General constructor.
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
     * @param \Magento\Catalog\Helper\Image $imageHelper
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
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $dateHelper, $utilsHelper, $resource, $resourceCollection, $data);
        $this->taxConfig = $taxConfig;
        $this->resourceProduct = $resourceProduct;
        $this->storeManager = $storeManager;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->localeDate = $localeDate;
        $this->productRepository = $productRepository;
        $this->taxCalculation = $taxCalculation;
        $this->objectManager = $objectManager;
        $this->productMetadata = $productMetadata;
        $this->imageHelper = $imageHelper;
    }


    public function getConfiguration()
    {
        // Reset cache
        self::$attributeSetCache = [];

        return [
            'name' => 'General product information',
            'category' => 'Product',
            'description' => 'Export extended product information.',
            'enabled' => true,
            'apply_to' => [
                \Xtento\ProductExport\Model\Export::ENTITY_PRODUCT
            ],
        ];
    }

    public function getExportData($entityType, $collectionItem)
    {
        // Set return array
        $returnArray = [];
        $this->writeArray = & $returnArray; // Write directly on product level
        // Fetch fields to export
        $product = $collectionItem->getProduct();

        if ($product->getTypeId() && $this->getProfile() && in_array($product->getTypeId(), explode(",", $this->getProfile()->getExportFilterProductType()))) {
            return $returnArray; // Product type should be not exported
        }

        // Timestamps of creation/update
        if ($this->fieldLoadingRequired('created_at_timestamp')) $this->writeValue('created_at_timestamp', $this->dateHelper->convertDateToStoreTimestamp($product->getCreatedAt()));
        if ($this->fieldLoadingRequired('updated_at_timestamp')) $this->writeValue('updated_at_timestamp', $this->dateHelper->convertDateToStoreTimestamp($product->getUpdatedAt()));

        // Which line is this?
        $this->writeValue('line_number', $collectionItem->currItemNo);
        $this->writeValue('count', $collectionItem->collectionSize);

        // Export information
        $this->writeValue('export_id', ($this->_registry->registry('productexport_log')) ? $this->_registry->registry('productexport_log')->getId() : 0);

        $this->exportProductData($product, $returnArray);

        // Done
        return $returnArray;
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     * @param $returnArray
     */
    protected function exportProductData($product, &$returnArray)
    {
        if ($this->getStoreId()) {
            $product->setStoreId($this->getStoreId());
            $this->writeValue('store_id', $this->getStoreId());
        } else {
            $this->writeValue('store_id', 0);
        }

        $exportAllFields = false;
        if ($this->getProfile()->getOutputType() == 'xml') {
            $exportAllFields = true;
        }

        #\Zend_Debug::dump($product->getData()); die();
        foreach ($product->getData() as $key => $value) {
            if ($key == 'entity_id') {
                continue;
            }
            if ($key == 'price') {
                $this->writeValue('original_price', $value);
                continue;
            }
            if (!$this->fieldLoadingRequired($key)) {
                if ($this->fieldLoadingRequired($key . '_raw') && !$exportAllFields) {
                    $this->writeValue($key . '_raw', $value);
                }
                continue;
            }
            if ($key == 'cost') {
                $this->writeValue('cost', $this->resourceProduct->getAttributeRawValue($product->getId(), 'cost', $this->getStoreId()));
                continue;
            }
            if ($key == 'min_price' || $key == 'max_price' || $key == 'special_price') {
                $value = $this->addTax($product, $value, $key);
            }
            if ($key == 'qty') {
                $value = sprintf('%d', $value);
            }
            if ($key == 'image' || $key == 'small_image' || $key == 'thumbnail') {
                $this->writeValue($key . '_raw', $value);
                $this->writeValue(
                    $key, $this->storeManager->getStore($this->getStoreId())
                            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/' . ltrim(
                            $value,
                            '/'
                        )
                );
                $this->writeValue(
                    $key . '_cache_url', $this->imageHelper->init($product, $key)
                    ->setImageFile($value)
                    ->getUrl()
                );
                continue;
            }
            $attribute = $product->getResource()->getAttribute($key);
            if ($attribute instanceof \Magento\Catalog\Model\ResourceModel\Eav\Attribute) {
                $attribute->setStoreId($product->getStoreId());
            }
            #if ($key == 'test') {
            #    var_dump($product->getAttributeText($key), $attribute->getStoreLabel($product->getStore()), $attribute);
            #    die();
            #}
            $attrText = '';
            if ($attribute) {
                if ($attribute->getFrontendInput() === 'media_image') {
                    $this->writeValue($key . '_raw', $value);
                    $this->writeValue(
                        $key, $this->storeManager->getStore($this->getStoreId())
                                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/' . ltrim(
                                $value,
                                '/'
                            )
                    );
                    continue;
                }
                if ($attribute->getFrontendInput() === 'weee' || $attribute->getFrontendInput() === 'media_gallery') {
                    // Don't export certain frontend_input values
                    continue;
                }
                try {
                    $attrText = $product->getAttributeText($key);
                } catch (\Exception $e) {
                    //echo "Problem with attribute $key: ".$e->getMessage();
                    continue;
                }
            }
            if (!empty($attrText)) {
                if (is_array($attrText)) {
                    // Multiselect:
                    foreach ($attrText as $index => $val) {
                        if (!is_array($index) && !is_array($val)) {
                            $this->writeValue($key . '_value_' . $index, $val);
                        }
                    }
                    $this->writeValue($key, implode(",", $attrText));
                } else {
                    if ($attribute->getFrontendInput() == 'multiselect') {
                        $this->writeValue($key . '_value_0', $attrText);
                    }
                    $this->writeValue($key, $attrText);
                }
            } else {
                $this->writeValue($key, $value);
            }
            if ($key == 'visibility' || $key == 'status' || $key == 'tax_class_id' || ($this->fieldLoadingRequired($key.'_raw') && !$exportAllFields)) {
                $this->writeValue($key . '_raw', $value);
            }
        }

        // Extended fields
        if ($this->fieldLoadingRequired('product_url')) {
            $productUrl = $product->getProductUrl(false);
            if ($this->getProfile()->getExportUrlRemoveStore()) {
                if (preg_match("/&/", $productUrl)) {
                    $productUrl = preg_replace("/___store=(.*?)&/", "&", $productUrl);
                } else {
                    $productUrl = preg_replace("/\?___store=(.*)/", "", $productUrl);
                }
            }
            $this->writeValue('product_url', $productUrl);
        }
        if ($this->fieldLoadingRequired('price')) {
            $this->writeValue('price', $this->getPrice($product));
        }
        if ($this->fieldLoadingRequired('final_price')) {
            $this->writeValue('final_price', $product->getPriceInfo()->getPrice('final_price')->getValue());
        }
        if ($this->fieldLoadingRequired('catalogrule_price')) {
            // Unfortunately the code in CatalogRule\Pricing\Price\CatalogPriceRule ignores the current scope :(
            $catalogRulePrice = $this->objectManager->get('Magento\CatalogRule\Model\ResourceModel\Rule')
                ->getRulePrice(
                    $this->objectManager->get('Magento\Framework\Stdlib\DateTime\TimezoneInterface')->scopeDate($this->getStoreId()),
                    $this->storeManager->getStore($this->getStoreId())->getWebsiteId(),
                    $this->getProfile()->getCustomerGroupId() ?: 0,
                    $product->getId()
                );
            $catalogRulePrice = $catalogRulePrice ? floatval($catalogRulePrice) : null;
            $this->writeValue('catalogrule_price', $catalogRulePrice);
            //if ($catalogRulePrice) {
            //    $catalogRulePrice = $this->priceCurrency->convertAndRound($catalogRulePrice, $this->getStoreId());
            //}
        }
        if ($this->fieldLoadingRequired('attribute_set_name')) {
            $attributeSetId = $product->getAttributeSetId();
            if (!array_key_exists($attributeSetId, self::$attributeSetCache)) {
                $attributeSet = $this->attributeSetFactory->create()->load($attributeSetId);
                $attributeSetName = '';
                if ($attributeSet->getId()) {
                    $attributeSetName = $attributeSet->getAttributeSetName();
                    $this->writeValue('attribute_set_name', $attributeSetName);
                }
                self::$attributeSetCache[$attributeSetId] = $attributeSetName;
            } else {
                $this->writeValue('attribute_set_name', self::$attributeSetCache[$attributeSetId]);
            }
        }

        // Upsell product IDs / SKUs
        if ($this->fieldLoadingRequired('upsell_product_ids') && !$exportAllFields) {
            $this->writeValue('upsell_product_ids', implode(",", $product->getUpSellProductIds()));
        }
        if ($this->fieldLoadingRequired('upsell_product_skus') && !$exportAllFields) {
            $skus = [];
            foreach ($product->getUpSellProductCollection() as $upsellProduct) {
                $skus[] = $upsellProduct->getSku();
            }
            $this->writeValue('upsell_product_skus', implode(",", $skus));
        }
        // Cross-Sell product IDs / SKUs
        if ($this->fieldLoadingRequired('cross_sell_product_ids') && !$exportAllFields) {
            $this->writeValue('cross_sell_product_ids', implode(",", $product->getCrossSellProductIds()));
        }
        if ($this->fieldLoadingRequired('cross_sell_product_skus') && !$exportAllFields) {
            $skus = [];
            foreach ($product->getCrossSellProductCollection() as $crosssellProduct) {
                $skus[] = $crosssellProduct->getSku();
            }
            $this->writeValue('cross_sell_product_skus', implode(",", $skus));
        }
        // Related product IDs / SKUs
        if ($this->fieldLoadingRequired('related_product_ids') && !$exportAllFields) {
            $this->writeValue('related_product_ids', implode(",", $product->getRelatedProductIds()));
        }
        if ($this->fieldLoadingRequired('related_product_skus') && !$exportAllFields) {
            $skus = [];
            foreach ($product->getRelatedProductCollection() as $relatedProduct) {
                $skus[] = $relatedProduct->getSku();
            }
            $this->writeValue('related_product_skus', implode(",", $skus));
        }
        if ($this->fieldLoadingRequired('website_codes') && !$exportAllFields) {
            $websiteCodes = [];
            foreach ($product->getWebsiteIds() as $websiteId) {
                $websiteCode = $this->storeManager->getWebsite($websiteId)->getCode();
                $websiteCodes[$websiteCode] = $websiteCode;
            }
            $this->writeValue('website_codes', join(',', $websiteCodes));
        }
        // Is special price active?
        if ($this->fieldLoadingRequired('special_price_active') && !$exportAllFields) {
            $dateToday = $this->localeDate->date();
            $dateToday->setTime(0, 0, 0);
            $isSpecialPriceActive = true;
            if ($product->getSpecialFromDate()) {
                $fromDate = $this->localeDate->date(new \DateTime($product->getSpecialFromDate()));
                $fromDate->setTime(0, 0, 0);
                if ($dateToday < $fromDate) {
                    $isSpecialPriceActive = false;
                }
            } else {
                $isSpecialPriceActive = false;
            }
            if ($product->getSpecialToDate()) {
                $toDate = $this->localeDate->date(new \DateTime($product->getSpecialToDate()));
                $toDate->setTime(0, 0, 0);
                if ($dateToday > $toDate) {
                    $isSpecialPriceActive = false;
                }
            }
            $this->writeValue('special_price_active', (int)$isSpecialPriceActive);
        }

        if ($this->fieldLoadingRequired('images') && !$exportAllFields) {
            $returnArray['images'] = [];
            $originalWriteArray = & $this->writeArray;
            $this->writeArray = & $returnArray['images'];
            if (version_compare($this->productMetadata->getVersion(), '2.1', '<')) {
                // Magento 2.0
                if (self::$mediaGalleryBackend === false) {
                    $attributes = $product->getTypeInstance()->getSetAttributes($product);
                    if (isset($attributes['media_gallery'])) {
                        self::$mediaGalleryBackend = $attributes['media_gallery']->getBackend();
                    }
                }
                if (self::$mediaGalleryBackend !== false) {
                    self::$mediaGalleryBackend->afterLoad($product);
                    $mediaGalleryImages = $product->getMediaGalleryImages();
                    if (is_array($mediaGalleryImages)) {
                        foreach ($mediaGalleryImages as $mediaGalleryImage) {
                            $this->writeArray = &$returnArray['images'][];
                            foreach ($mediaGalleryImage->getData() as $key => $value) {
                                $this->writeValue($key, $value);
                            }
                        }
                    }
                }
            } else {
                // Magento 2.1+, no DI possible as it does not exist for Magento 2.0
                /** @var \Magento\Catalog\Model\Product\Gallery\ReadHandler $galleryReadHandler */
                $galleryReadHandler = $this->objectManager->get('\Magento\Catalog\Model\Product\Gallery\ReadHandler');
                $galleryReadHandler->execute($product);
                $mediaGalleryImages = $product->getMediaGalleryImages();
                // ReadHandler only loads disabled=0 images, meaning, hidden images are not exported. To fix this, you must load the full product like this:
                /*
                $product = $product->load($product->getId());
                $directory = $this->objectManager->get('\Magento\Framework\Filesystem')->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                $mediaGalleryImages = $this->objectManager->create('\Magento\Framework\Data\CollectionFactory')->create();
                $mediaConfig = $this->objectManager->get('\Magento\Catalog\Model\Product\Media\Config');
                foreach ($product->getMediaGallery('images') as $image) {
                    if (empty($image['value_id']) || $mediaGalleryImages->getItemById($image['value_id']) != null) {
                        continue;
                    }
                    $image['url'] = $mediaConfig->getMediaUrl($image['file']);
                    $image['id'] = $image['value_id'];
                    $image['path'] = $directory->getAbsolutePath($mediaConfig->getMediaPath($image['file']));
                    $mediaGalleryImages->addItem(new \Magento\Framework\DataObject($image));
                }
                */
                if (!empty($mediaGalleryImages)) {
                    foreach ($mediaGalleryImages as $mediaGalleryImage) {
                        $this->writeArray = &$returnArray['images'][];
                        foreach ($mediaGalleryImage->getData() as $key => $value) {
                            $this->writeValue($key, $value);
                        }
                        // Get correct image URL for store
                        /*$storeImageUrl = $this->storeManager->getStore($this->getStoreId())->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/' . ltrim(str_replace('\\', '/', $mediaGalleryImage['file']), '/');
                        $this->writeValue('url_store', $storeImageUrl);*/
                    }
                }
            }
            $this->writeArray = & $originalWriteArray;
        }

        // Get custom options
        if ($this->fieldLoadingRequired('custom_options') && !$exportAllFields) {
            $returnArray['custom_options'] = [];
            $originalWriteArray = & $this->writeArray;
            $this->writeArray = & $returnArray['custom_options'];
            // Unfortunately you can only fetch custom options with the product being loaded. No way to add all the fields on collection load.
            $product->load($product->getId());
            $productOptions = $product->getOptions();
            if (is_array($productOptions)) {
                foreach ($productOptions as $productOption) {
                    $customOption = & $returnArray['custom_options'][];
                    $this->writeArray = & $customOption;
                    foreach ($productOption->getData() as $key => $value) {
                        $this->writeValue($key, $value);
                    }
                    $optionValues = $productOption->getValues();
                    if (is_array($optionValues)) {
                        $this->writeArray = & $customOption['values'];
                        foreach ($optionValues as $optionValue) {
                            $this->writeArray = & $customOption['values'][];
                            foreach ($optionValue->getData() as $key => $value) {
                                $this->writeValue($key, $value);
                            }
                        }
                    }
                }
            }
            $this->writeArray = & $originalWriteArray;
        }

        // Tier prices
        if ($this->fieldLoadingRequired('tier_prices') && !$exportAllFields) {
            $returnArray['tier_prices'] = [];
            $originalWriteArray = & $this->writeArray;
            $this->writeArray = & $returnArray['tier_prices'];
            $attribute = $product->getResource()->getAttribute('tier_price');

            if ($attribute) {
                $attribute->getBackend()->afterLoad($product);
                $tierPrices = $product->getData('tier_price');
                if (is_array($tierPrices)) {
                    foreach ($tierPrices as $tierPrice) {
                        $tierPriceNode = & $returnArray['tier_prices'][];
                        $this->writeArray = & $tierPriceNode;
                        foreach ($tierPrice as $key => $value) {
                            $this->writeValue($key, $value);
                        }
                    }
                }
            }
            $this->writeArray = & $originalWriteArray;
        }

        // Group prices
        if ($this->fieldLoadingRequired('group_prices') && !$exportAllFields) {
            $returnArray['group_prices'] = [];
            $originalWriteArray = & $this->writeArray;
            $this->writeArray = & $returnArray['group_prices'];
            $attribute = $product->getResource()->getAttribute('group_price');

            if ($attribute) {
                $attribute->getBackend()->afterLoad($product);
                $groupPrices = $product->getData('group_price');
                if (is_array($groupPrices)) {
                    foreach ($groupPrices as $groupPrice) {
                        $groupPriceNode = & $returnArray['group_prices'][];
                        $this->writeArray = & $groupPriceNode;
                        foreach ($groupPrice as $key => $value) {
                            $this->writeValue($key, $value);
                        }
                    }
                }
            }
            $this->writeArray = & $originalWriteArray;
        }

        /** Customer group prices - M1, to be ported */
        /*if ($this->fieldLoadingRequired('price_customer_group')) {
            $product = Mage::getModel('catalog/product')->load($product->getId());
            $originalCustomerGroupId = $product->getCustomerGroupId();
            foreach (Mage::getModel('customer/group')->getCollection() as $customerGroup) {
                $groupId = $customerGroup->getCustomerGroupId();
                if (!$this->fieldLoadingRequired('price_customer_group_' . $groupId)) {
                    continue;
                }
                $product->setCustomerGroupId($groupId);
                $this->writeValue(
                    'price_customer_group_' . $groupId,
                    $this->_getPrice($product, 'price_customer_group_' . $groupId)
                );
            }
            // Reset group ID
            $product->setCustomerGroupId($originalCustomerGroupId);
        }*/
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     * @param string $key
     *
     * @return int
     */
    protected function getPrice($product, $key = 'price')
    {
        $price = $product->getFinalPrice();
        if ($price == 0) {
            $price = $product->getMinPrice();
        }
        $price = $this->addTax($product, $price, $key);
        return $price;
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     * @param float $price
     * @param string $key
     *
     * @return int
     */
    protected function addTax($product, $price, $key)
    {
        $taxPercent = false;
        if ($product->getTaxPercent()) {
            $taxPercent = $product->getTaxPercent();
        } else {
            $taxPercent = false;
            if ($product->getTypeId() == 'grouped') {
                // Get tax_percent from child product
                $childProductIds = $product->getTypeInstance()->getChildrenIds($product->getId());
                if (is_array($childProductIds)) {
                    $childProductIds = array_shift($childProductIds);
                    if (is_array($childProductIds)) {
                        $childProductId = array_shift($childProductIds);
                        try {
                            $childProduct = $this->productRepository->getById($childProductId, false, $this->getStoreId());
                            if ($childProduct->getId()) {
                                $request = $this->taxCalculation->getRateRequest(false, false, false, $product->getStore());
                                $taxPercent = $this->taxCalculation->getRate($request->setProductClassId($childProduct->getTaxClassId()));
                            }
                        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {}
                    }
                }
            }
        }
        if ($taxPercent > 0) {
            if (!$this->taxConfig->priceIncludesTax($this->getStoreId())) {
                // Write price excl. tax
                $this->writeValue($key . '_excl_tax', $price);
                // Prices are excluding tax -> add tax
                $price *= 1 + $taxPercent / 100;
            } else {
                // Prices are including tax - do not add tax to price
                // Write price excl. tax
                $this->writeValue($key . '_excl_tax', $price / (1 + $taxPercent / 100));
            }
        } else {
            $this->writeValue($key . '_excl_tax', $price);
        }
        return $price;
    }
}