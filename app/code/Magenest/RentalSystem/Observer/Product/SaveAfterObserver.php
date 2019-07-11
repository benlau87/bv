<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Observer\Product;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magenest\RentalSystem\Model\Rental;
use Magenest\RentalSystem\Model\RentalFactory;
use Magenest\RentalSystem\Model\RentalPriceFactory;
use Magenest\RentalSystem\Model\RentalOptionFactory;
use Magenest\RentalSystem\Model\RentalOptionTypeFactory;
use Magenest\RentalSystem\Model\RentalOrderFactory;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ProductMetadataInterface;

class SaveAfterObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManage;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var RentalFactory
     */
    protected $_rentalFactory;

    /**
     * @var RentalPriceFactory
     */
    protected $_rentalPriceFactory;

    /**
     * @var RentalOptionFactory
     */
    protected $_rentalOptionFactory;

    /**
     * @var RentalOptionTypeFactory
     */
    protected $_rentalOptionTypeFactory;

    /**
     * @var RentalOrderFactory
     */
    protected $_rentalOrderFactory;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * SaveAfterObserver constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param RentalFactory $rentalFactory
     * @param RentalPriceFactory $rentalPriceFactory
     * @param RentalOptionFactory $rentalOptionFactory
     * @param RentalOptionTypeFactory $rentalOptionTypeFactory
     * @param RentalOrderFactory $rentalOrderFactory
     * @param Context $context
     * @param ResourceConnection $resource
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        RentalFactory $rentalFactory,
        RentalPriceFactory $rentalPriceFactory,
        RentalOptionFactory $rentalOptionFactory,
        RentalOptionTypeFactory $rentalOptionTypeFactory,
        RentalOrderFactory $rentalOrderFactory,
        Context $context,
        ResourceConnection $resource,
        ProductMetadataInterface $productMetadata,
        \Magento\Framework\Registry $registry
    ) {
        $this->_storeManager            = $storeManager;
        $this->_rentalFactory           = $rentalFactory;
        $this->_rentalPriceFactory      = $rentalPriceFactory;
        $this->_rentalOptionFactory     = $rentalOptionFactory;
        $this->_rentalOptionTypeFactory = $rentalOptionTypeFactory;
        $this->_rentalOrderFactory      = $rentalOrderFactory;
        $this->context                  = $context;
        $this->_request                 = $context->getRequest();
        $this->messageManager           = $context->getMessageManager();
        $this->resource                 = $resource;
        $this->productMetadata          = $productMetadata;
        $this->registry                 = $registry;
    }

    /**
     * @param Observer $observer
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product       = $observer->getEvent()->getProduct();
        $productId     = $product->getId();
        $status        = $product->getStatus();
        $productTypeId = $product->getTypeId();
        $params        = $this->_request->getParams();
        $priceUpdated  = $this->registry->registry('price_updated' . $productId);

        if (@$params['rental'] && $productTypeId == Rental::PRODUCT_TYPE && $priceUpdated != true) {
            $magentoVer = $this->productMetadata->getVersion();
            if (isset($params['sources']) && version_compare($magentoVer, '2.3', '>=')) {
                $totalQty = $this->checkQty($product, $params['sources']);
            } else {
                $totalQty = $product->getStockData();
                $totalQty = @$totalQty['qty'] ? $totalQty['qty'] : 0;
            }

            $data  = $params['rental'];
            $model = $this->_rentalFactory->create()->load($productId, 'product_id');

            if (@$data['type'] == 'local_pickup') {
                $data['type']      = 1;
                $data['lead_time'] = null;
            } else {
                $data['type']           = 0;
                $data['pickup_address'] = null;
            }

            $data['product_id']   = $productId;
            $data['product_name'] = $product->getName();
//            if (empty($model->getInitialQty()))
//                $data['initial_qty'] = $totalQty;
//            else $data['initial_qty'] = $model->getInitialQty();


            //Handle update initial qty for rent when updating product qty
            if (empty($model->getInitialQty()))
                $data['initial_qty'] = $totalQty;
            else {
                $stockData = $product->getStockData();
                $qtyChange           = $stockData['qty'] - $model->getAvailableQty();
                $data['initial_qty'] = $model->getInitialQty() + $qtyChange;
            }

            $rentedQty = $this->checkRentedQty($model);

            $data['available_qty'] = @$data['initial_qty'] - $rentedQty;
            $model->addData($data);
            $model->save();

            if (!empty($data['row'])) {
                if ($data['row'][0]['base_period'] != null) {
                    $data['row'][0]['base_period'] = $data['row'][0]['base_period'] . $data['row'][0]['base_period_unit'];
                }
                if ($data['row'][0]['additional_period'] != null) {
                    $data['row'][0]['additional_period'] = $data['row'][0]['additional_period'] . $data['row'][0]['additional_period_unit'];
                }
                $this->saveRentalPrice($data['row'], $model->getData());
            }
            if (!empty($data['additional_options']))
                $this->saveRentalOption($data['additional_options'], $model->getData());
            else
                $this->deleteAllOptions($productId);

            $basePrice = @$data['row'][0]['base_price'];

            if (!empty($basePrice)) {
                $product->setPrice($basePrice);
                $product->setBasePrice($basePrice);
                $this->registry->register('price_updated' . $productId, true);
                $product->save();
            }
        }

        return;
    }

    /**
     * @param array $priceData
     * @param array $model
     *
     * @throws \Exception
     */
    public function saveRentalPrice($priceData, $model)
    {
        $priceModel = $this->_rentalPriceFactory->create()->load($model['id'], 'rental_id');
        foreach ($priceData as $price) {
            $price['rental_id']  = $model['id'];
            $price['product_id'] = $model['product_id'];
            $price['type']       = 0;
            $priceModel->addData($price);
            $priceModel->save();
        }
    }

    /**
     * @param array $options
     * @param array $model
     *
     * @throws \Exception
     */
    public function saveRentalOption($options, $model)
    {
        foreach ($options as $option) {
            if (!isset($once)) {
                $once = true;
            }
            if (isset($option['is_delete']) && @$option['is_delete'] == 1) {
                $this->_rentalOptionFactory->create()->getCollection()
                    ->addFilter('product_id', $model['product_id'])
                    ->addFilter('option_id', $option['record_id'])
                    ->walk('delete');
                $this->_rentalOptionTypeFactory->create()->getCollection()
                    ->addFilter('product_id', $model['product_id'])
                    ->addFilter('option_id', $option['option_id'])
                    ->walk('delete');
                continue;
            } else {
                if ($once) {
                    $currentRecord = [];
                    $oldRecord     = [];
                    foreach ($options as $optionCheck) {
                        if (empty($optionCheck['id_option']))
                            continue;
                        array_push($currentRecord, intval($optionCheck['id_option']));
                    }
                    $oldOptions = $this->_rentalOptionFactory->create()->getCollection()
                        ->addFilter('product_id', $model['product_id']);
                    foreach ($oldOptions as $oldOption)
                        array_push($oldRecord, $oldOption->getId());

                    $toDeleteOptions = array_diff($oldRecord, $currentRecord);
                    foreach ($toDeleteOptions as $deleteOption) {
                        $this->_rentalOptionFactory->create()->load($deleteOption)->delete();
                        $this->deleteOptionTypes($model['product_id'], $deleteOption);
                    }
                    $once = false;
                }
            }

            $modelOption = $this->_rentalOptionFactory->create();
            if (!empty($option['id_option']))
                $modelOption->load($option['id_option']);
            if (strtolower($option['option_title']) == 'select') {
                $option['option_title'] = "Options";
                $this->messageManager->addNoticeMessage('Cannot save option title is "Select".');
            }

            $type = @$option['type'];

            $data = [
                'rental_id'    => @$model['id'],
                'product_id'   => @$model['product_id'],
                'option_id'    => @$option['record_id'],
                'option_title' => @$option['option_title'],
                'type'         => $type,
                'is_required'  => @$option['is_required'],
            ];
            $modelOption->addData($data)->save();
            if (!empty($option['row']))
                $this->saveRentalOptionType($option['row'], $modelOption->getData());
        }
    }

    /**
     * @param array $types
     * @param array $modelOption
     *
     * @throws \Exception
     */
    public function saveRentalOptionType($types, $modelOption)
    {
        foreach ($types as $typeOptions) {
            if (!isset($once)) {
                $once = true;
            }
            if (isset($typeOptions['is_delete']) && $typeOptions['is_delete'] == 1) {
                $modelType = $this->_rentalOptionTypeFactory->create()->load(@$typeOptions['id']);
                $modelType->delete();
                continue;
            } else {
                if ($once) {
                    $currentRecord = [];
                    $oldRecord     = [];
                    foreach ($types as $typeCheck) {
                        if (empty($typeCheck['id_type']))
                            continue;
                        array_push($currentRecord, intval($typeCheck['id_type']));
                    }
                    $oldOptions = $this->_rentalOptionTypeFactory->create()->getCollection()
                        ->addFilter('product_id', $modelOption['product_id'])
                        ->addFilter('option_id', $modelOption['id']);
                    foreach ($oldOptions as $oldOption)
                        array_push($oldRecord, $oldOption->getId());
                    $toDeleteOptions = array_diff($oldRecord, $currentRecord);
                    foreach ($toDeleteOptions as $deleteOption)
                        $this->_rentalOptionTypeFactory->create()->load($deleteOption)->delete();

                    $once = false;
                }
            }

            $modelType = $this->_rentalOptionTypeFactory->create();
            if (!empty($typeOptions['id_type']))
                $modelType->load($typeOptions['id_type']);
            $data = [
                'option_title'  => @$typeOptions['option'],
                'option_id'     => @$modelOption['id'],
                'product_id'    => @$modelOption['product_id'],
                'option_number' => @$typeOptions['record_id'],
                'price'         => @$typeOptions['price'],
            ];

            $modelType->addData($data)->save();
        }
    }

    /**
     * @param object $model
     *
     * @return int
     */
    public function checkRentedQty($model)
    {
        if (!empty($model->getId())) {
            $rentedUnits = $this->_rentalOrderFactory->create()->getCollection()
                ->addFieldToFilter('rental_id', $model->getId())
                ->addFieldToFilter('status', ['neq' => 2])
                ->getAllIds();

            if (!empty($rentedUnits))
                return count($rentedUnits);
            else
                return 0;
        }

        return 0;
    }

    /**
     * Check Qty on Magento 2.3 MSI
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param $sources
     *
     * @return null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkQty($product, $sources)
    {
        $websiteCode = $this->storeManage->getWebsite(1)->getCode();
        /** @var  \Magento\InventorySales\Model\ResourceModel\GetAssignedStockIdForWebsite $getAssignedStockId */
        $getAssignedStockId = ObjectManager::getInstance()
            ->create('Magento\InventorySales\Model\ResourceModel\GetAssignedStockIdForWebsite');
        $websiteStockId     = $getAssignedStockId->execute($websiteCode); /*get default website's stock Id*/

        $qty = null;
        if (!empty($sources['assigned_sources'])) {
            foreach ($sources['assigned_sources'] as $source) { /*get stock qty per valid source*/
                $sourceCode = @$source['source_code'];
                if ($this->validateSource($websiteStockId, $sourceCode) && @$source['status'] == 1) {
                    $qty += @$source['quantity'];
                }
            }
        }

        $currentQty = $product->getStockData();
        $currentQty = @$currentQty['qty'];
        if ($product->getStatus() == 1) {
            $currentSalableQty = $this->getSalableQty($product->getSku());
            if (isset($currentQty) && isset($currentSalableQty)) {
                $pendingQty = $currentQty - $currentSalableQty; /*sold but not delivered qty*/
                $qty        = $pendingQty > 0 ? $qty - $pendingQty : $qty; /*get updated salable qty*/
            }
        }

        return $qty;
    }

    /**
     * @param $sku
     *
     * @return int
     */
    public function getSalableQty($sku)
    {
        $getProductSalableQty         = ObjectManager::getInstance()
            ->create('Magento\InventorySalesApi\Api\GetProductSalableQtyInterface');
        $getStockItemConfiguration    = ObjectManager::getInstance()
            ->create('Magento\InventoryConfigurationApi\Api\GetStockItemConfigurationInterface');
        $storeManager                 = ObjectManager::getInstance()
            ->create('Magento\Store\Model\StoreManagerInterface');
        $websiteCode                  = $storeManager->getWebsite(1)->getCode();
        $getAssignedStockIdForWebsite = ObjectManager::getInstance()
            ->create('Magento\InventorySales\Model\ResourceModel\GetAssignedStockIdForWebsite');

        $websiteStockId         = $getAssignedStockIdForWebsite->execute($websiteCode); /*get default website's stock Id*/
        $stockItemConfiguration = $getStockItemConfiguration->execute($sku, $websiteStockId);
        $isManageStock          = $stockItemConfiguration->isManageStock(); /*check if stock is in use*/
        $stockQty               = $isManageStock ? $getProductSalableQty->execute($sku, $websiteStockId) : 0; /*get salable qty of product's valid stock*/
        $qty                    = $stockQty;

        return $qty;
    }

    /**
     * Validate input source for base website
     *
     * @param $stockId
     * @param $sourceCode
     *
     * @return bool
     */
    public function validateSource($stockId, $sourceCode)
    {
        $resource             = $this->resource;
        $connection           = $resource->getConnection();
        $stockSourceLinkTable = $resource->getTableName('inventory_source_stock_link'); /*gives table name with prefix*/
        $sourceCode           = '"' . $sourceCode . '"';
        $sql                  = "Select stock_id FROM " . $stockSourceLinkTable . " Where source_code = " . $sourceCode;
        $sourceStockId        = $connection->fetchOne($sql); /*get stock Id linked to this source*/
        if ($sourceStockId == $stockId) {
            return true;
        } else return false;
    }

    /**
     * @param int $productId
     */
    public function deleteAllOptions($productId)
    {
        $this->_rentalOptionFactory->create()->getCollection()
            ->addFilter('product_id', $productId)->walk('delete');

        $this->deleteOptionTypes($productId);
    }

    /**
     * @param int $productId
     * @param int|null $optionId
     */
    public function deleteOptionTypes($productId, $optionId = null)
    {
        if (!isset($optionId)) {
            $this->_rentalOptionTypeFactory->create()->getCollection()
                ->addFilter('product_id', $productId)->walk('delete');
        } else {
            $this->_rentalOptionTypeFactory->create()->getCollection()
                ->addFilter('product_id', $productId)
                ->addFilter('option_id', $optionId)->walk('delete');
        }
    }
}