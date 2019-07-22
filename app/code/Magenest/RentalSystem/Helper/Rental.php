<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Helper;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\MailException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magenest\RentalSystem\Model\RentalFactory;
use Magenest\RentalSystem\Model\RentalOrderFactory;
use Magenest\RentalSystem\Model\RentalOptionFactory;
use Magenest\RentalSystem\Model\RentalOptionTypeFactory;
use Magenest\RentalSystem\Model\RentalPriceFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Area;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Class Rental
 * @package Magenest\RentalSystem\Helper
 */
class Rental extends AbstractHelper
{
    /**
     * Const Email
     */
    const XML_PATH_EMAIL_SENDER = 'trans_email/ident_general/email';

    /**
     * Const Name
     */
    const XML_PATH_NAME_SENDER = 'trans_email/ident_general/name';

    /**
     * Global max duration
     */
    const XML_PATH_MAX_DURATION = 'rental_system/rental/max_duration';

    /**
     * @var RentalFactory
     */
    protected $_rentalFactory;

    /**
     * @var RentalOrderFactory
     */
    protected $_rentalOrderFactory;

    /**
     * @var RentalOptionFactory
     */
    protected $_rentalOptionFactory;

    /**
     * @var RentalOptionTypeFactory
     */
    protected $_rentalOptionTypeFactory;

    /**
     * @var RentalPriceFactory
     */
    protected $_rentalPriceFactory;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var StateInterface
     */
    protected $_inlineTranslation;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockStateInterface;

    /**
     * @var \Magento\CatalogInventory\Api\StockRegistryInterface
     */
    protected $_stockRegistry;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * @var TimezoneInterface
     */
    protected $_timezone;

    /**
     * Rental constructor.
     *
     * @param Context $context
     * @param RentalFactory $rentalFactory
     * @param RentalOrderFactory $rentalOrderFactory
     * @param RentalOptionFactory $rentalOptionFactory
     * @param RentalOptionTypeFactory $rentalOptionTypeFactory
     * @param RentalPriceFactory $rentalPriceFactory
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param StoreManagerInterface $storeManager
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockStateInterface
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\Catalog\Model\Product $product
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        Context $context,
        RentalFactory $rentalFactory,
        RentalOrderFactory $rentalOrderFactory,
        RentalOptionFactory $rentalOptionFactory,
        RentalOptionTypeFactory $rentalOptionTypeFactory,
        RentalPriceFactory $rentalPriceFactory,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        \Magento\CatalogInventory\Api\StockStateInterface $stockStateInterface,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Catalog\Model\Product $product,
        TimezoneInterface $timezone
    ) {
        parent::__construct($context);
        $this->_rentalFactory           = $rentalFactory;
        $this->_rentalOrderFactory      = $rentalOrderFactory;
        $this->_rentalOptionFactory     = $rentalOptionFactory;
        $this->_rentalOptionTypeFactory = $rentalOptionTypeFactory;
        $this->_rentalPriceFactory      = $rentalPriceFactory;
        $this->_transportBuilder        = $transportBuilder;
        $this->_inlineTranslation       = $inlineTranslation;
        $this->_storeManager            = $storeManager;
        $this->_scopeConfig             = $context->getScopeConfig();
        $this->_stockStateInterface     = $stockStateInterface;
        $this->_stockRegistry           = $stockRegistry;
        $this->_product                 = $product;
        $this->_timezone                = $timezone;

    }

    /**
     * @param $productId
     *
     * @return bool
     */
    public function isRental($productId)
    {
        $rentalId = $this->_rentalFactory->create()->load($productId, 'product_id')->getId();
        if (!empty($rentalId))
            return true;
        else
            return false;
    }

    /**
     * Get rentable qty of rental product
     *
     * @param $productId
     *
     * @return mixed
     */
    public function getAvailableQty($productId)
    {
        return $this->_rentalFactory->create()->loadByProductId($productId)->getData('available_qty');
    }

    /**
     * @param $id
     * @param $hours
     *
     * @return bool
     */
    public function validateDuration($id, $hours)
    {
        $rentalData  = $this->_rentalFactory->create()->loadByProductId($id)->getData();
        $maxDuration = !empty($rentalData['max_duration']) ? $rentalData['max_duration'] : $this->scopeConfig->getValue(self::XML_PATH_MAX_DURATION);
        $maxDuration = (int)$maxDuration * 24;
        if ($hours > $maxDuration)
            return false;
        else return true;
    }

    /**
     * @param $id
     * @param $additionalOptions
     *
     * @return bool
     */
    public function validatePrice($id, $additionalOptions)
    {
        $priceData = $this->_rentalPriceFactory->create()->loadByProductId($id)->getData();
        $basePrice = $priceData['base_price'];
        $baseHour  = $this->getDuration($priceData['base_period']);
        $addPrice  = @$priceData['additional_price'];
        $addHour   = $this->getDuration(@$priceData['additional_period']);

        $price = 0;

        $requestHours = $additionalOptions['rental_hours'];
        $requestPrice = $additionalOptions['rental_price'];
        if ($addPrice > 0 && $addHour > 0) {
            if ($requestHours <= $baseHour)
                $price = $basePrice;
            else $price = $basePrice + ceil(($requestHours - $baseHour) / $addHour) * $addPrice;
        } else $price = ceil($requestHours / $baseHour) * $basePrice;

        if (!empty($additionalOptions['options'])) {
            $options = $additionalOptions['options'];
            foreach ($options as $key => $value) {
                if (!empty($value)) {
                    $optionData = explode('_', $value);
                    $optionId   = $optionData[1];
                    $price      += $this->getOptionCost($requestHours, $optionId);
                }
            }
        }

        if ($price != $requestPrice)
            return false;
        else return true;
    }

    /**
     * @param $hours
     * @param $id
     *
     * @return float|int
     */
    protected function getOptionCost($hours, $id)
    {
        $optionTypeData = $this->_rentalOptionTypeFactory->create()->load($id)->getData();
        $typePrice      = $optionTypeData['price'];
        $optionId       = $optionTypeData['option_id'];
        $optionData     = $this->_rentalOptionFactory->create()->load($optionId)->getData();
        $optionsType    = $optionData['type'];

        switch ($optionsType) {
            case 'fixed' :
                return $typePrice;
            case 'per_day' :
                return ceil($hours / 24) * $typePrice;
            case 'per_hour' :
                return $hours * $typePrice;
        }

        return 0;
    }

    /**
     * @param string $period
     *
     * @return string
     */
    protected function getDuration($period)
    {
        $length   = strlen($period);
        $duration = (int)substr($period, 0, $length - 1);
        $type     = substr($period, -1);

        if ($type == 'w' || $type == 'W')
            return $duration * 168;
        else if ($type == 'D' || $type == 'd')
            return $duration * 24;
        else return $duration;
    }

    /**
     * @param $rentalProductId
     * @param $qty
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function updateRentalProductQty($rentalProductId, $qty = 0)
    {
        $rentalProductModel = $this->_rentalFactory->create()->load($rentalProductId);
        $availableQty       = $rentalProductModel->getData()['available_qty'];
        $productId          = $rentalProductModel->getData()['product_id'];
        $sku                = $this->_product->load($productId)->getData('sku');
        $updateQty          = $availableQty + $qty;
        $stockItem          = $this->_stockRegistry->getStockItemBySku($sku);
        $stockItem->setQty($updateQty);
        $this->_stockRegistry->updateStockItemBySku($sku, $stockItem);
    }

    /**
     * @param $rentalId
     * @param int $qtySubtract
     *
     * @throws \Exception
     */
    public function updateAvailableQty($rentalId, $qtySubtract = 0)
    {
        $model       = $this->_rentalFactory->create()->load($rentalId);
        $unavailable = 0;
        $rents       = $this->_rentalOrderFactory->create()->getCollection()
            ->addFieldToFilter('rental_id', $rentalId)
            ->addFieldToFilter('status', ['neq' => 2]);
        /* @var \Magenest\RentalSystem\Model\RentalOrder $rent */
        foreach ($rents as $rent) {
            $qty         = $rent->getData('qty');
            $unavailable += $qty;
        }

        $initialQty   = $model->getData('initial_qty');
        $availableQty = $initialQty - $unavailable;

        $model->setData('initial_qty', $initialQty - $qtySubtract)->setData('available_qty', $availableQty - $qtySubtract)->save();

    }

    /**
     * Update qty to return to stock, in case refund before return
     *
     * @param $rentalOrderId
     * @param $qty
     *
     * @throws \Exception
     */
    public function updateReturnQty($rentalOrderId, $qty)
    {
        $model      = $this->_rentalOrderFactory->create()->load($rentalOrderId);
        $currentQty = $model->getData('return_qty');
        $currentQty = isset($currentQty) ? $currentQty : 0;
        $model->setData('return_qty', $currentQty + $qty)->save();
    }

    /**
     * @param array $options
     *
     * @return string $data
     */
    public function decodeOptions($options)
    {
        $data = '';

        foreach ($options as $option) {
            $optionData = explode("_", $option);
            $optionId   = @$optionData[2];
            $typeId     = @$optionData[1];

            $optionTitle = $this->_rentalOptionFactory->create()->load($optionId)->getData('option_title');
            $typeTitle   = $this->_rentalOptionTypeFactory->create()->load($typeId)->getData('option_title');

            if (!empty($optionTitle) && !empty($typeTitle))
                $data = $data . $optionTitle . ': ' . $typeTitle . '. ';
        }

        return $data;
    }

    /**
     * @param $data
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function sendReceipt($data)
    {
        try {
            $rentalId = $data['rental_id'];
            $this->_inlineTranslation->suspend();
            $emailTemplate = $this->_rentalFactory->create()->getEmailTemplate($rentalId);

            $this->_transportBuilder->setTemplateIdentifier($emailTemplate)->setTemplateOptions(
                [
                    'area'  => Area::AREA_FRONTEND,
                    'store' => $this->_storeManager->getStore()->getId(),
                ]
            )->setTemplateVars(
                [
                    'store'              => $this->_storeManager->getStore(),
                    'store URL'          => $this->_storeManager->getStore()->getBaseUrl(),
                    'title'              => $data['title'],
                    'customer_name'      => $data['customer_name'],
                    'rental_code'        => $data['code'],
                    'order_id'           => $data['order_increment_id'],
                    'start_time'         => $this->_timezone->formatDateTime($data['start_time'], 3, 3),
                    'end_time'           => $this->_timezone->formatDateTime($data['end_time'], 3, 3),
                    'qty'                => $data['qty'],
                    'rent_type'          => $data['type'] == 0 ? 'Shipping' : 'Local Pickup',
                    'additional_options' => $data['information']
                ]
            )->setFrom(
                [
                    'email' => $this->_scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER),
                    'name'  => $this->_scopeConfig->getValue(self::XML_PATH_NAME_SENDER)
                ]
            )->addTo(
                $data['customer_email'],
                $data['customer_name']
            );

            $this->_transportBuilder->getTransport()->sendMessage();
            $this->_inlineTranslation->resume();

            return;

        } catch (MailException $e) {
            ObjectManager::getInstance()->create(LoggerInterface::class)->critical($e->getMessage());
        }
    }
}