<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Block\Order;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session as CustomerSession;
use Magenest\RentalSystem\Model\ResourceModel\RentalOrder\CollectionFactory as OrderCollectionFactory;
use Magenest\RentalSystem\Model\RentalFactory;
use Magento\Catalog\Model\ProductFactory;
use Magenest\RentalSystem\Model\RentalOptionFactory;
use Magenest\RentalSystem\Model\RentalOptionTypeFactory;
/**
 * Class History
 * @package Magenest\RentalSystem\Block\Order
 */
class History extends Template
{
    /**
     * Google Map API key
     */
    const XML_PATH_GOOGLE_MAP_API_KEY = 'rental_system/general/google_api_key';

    /**
     * Template
     * @var string
     */
    protected $_template = 'order/history.phtml';

    /**
     * @var OrderCollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var RentalFactory
     */
    protected $_rentalFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var ProductFactory
     */
    protected $_productFactory;

    /**
     * @var string
     */
    protected $rents;

    /**
     * @var RentalOptionFactory
     */
    protected $rentalOptionFactory;

    /**
     * @var RentalOptionTypeFactory
     */
    protected $rentalOptionTypeFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timezone;

    /**
     * History constructor.
     *
     * @param RentalOptionFactory $rentalOptionFactory
     * @param RentalOptionTypeFactory $rentalOptionTypeFactory
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param RentalFactory $rentalFactory
     * @param CustomerSession $customerSession
     * @param ProductFactory $productFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        RentalOptionFactory $rentalOptionFactory,
        RentalOptionTypeFactory $rentalOptionTypeFactory,
        OrderCollectionFactory $orderCollectionFactory,
        RentalFactory $rentalFactory,
        CustomerSession $customerSession,
        ProductFactory $productFactory,
        Template\Context $context,
        array $data = []
    ) {
        $this->rentalOptionFactory     = $rentalOptionFactory;
        $this->rentalOptionTypeFactory = $rentalOptionTypeFactory;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_rentalFactory          = $rentalFactory;
        $this->_customerSession        = $customerSession;
        $this->_productFactory         = $productFactory;
        $this->_timezone               = $context->getLocaleDate();
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Rents'));
    }

    /**
     * Get Rent Collection
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getRents()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        if (!$this->rents) {
            $this->rents = $this->_orderCollectionFactory->create()->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'customer_id',
                $customerId
            )->setOrder(
                'main_table.created_at',
                'desc'
            )->setOrder(
                'main_table.rental_id',
                'desc'
            );
            $rentalTable = $this->_orderCollectionFactory->create()->getTable('magenest_rental_product');
//            $this->rents->getSelect()->joinLeft(
//                ['rental' => $rentalTable],
//                'main_table.rental_id = rental.id ',
//                ['*']
//            );
        }

        return $this->rents;
    }

    /**
     * @param $productId
     *
     * @return mixed
     */
    public function getProductUrl($productId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product       = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
        $productUrl    = $product->getProductUrl();

        return $productUrl;
    }


    /**
     * @param $orderId
     *
     * @return string
     */
    public function getOrderUrl($orderId)
    {
        return $this->getUrl('sales/order/view', ['order_id' => $orderId]);
    }

    public function getDeliveryType($rentalId, $type)
    {
        $collection = $this->_rentalFactory->create()->load($rentalId);
        if ($type == 1) {
            $address = @$collection->getData('pickup_address');

            return $address;
        } elseif ($type == 0) {
            $leadtime = @$collection->getData('lead_time');

            return $leadtime;
        }

        return '';
    }

    /**
     * @param $rentalId
     *
     * @return mixed
     */
    public function getProductId($rentalId)
    {
        return $this->_rentalFactory->create()->load($rentalId)->getData('product_id');
    }

    /**
     * @param $rentalProductId
     *
     * @return array
     */
    public function getRentalProductOption($rentalProductId)
    {
        $optionCollection = $this->rentalOptionFactory->create()->getCollection()->addFieldToFilter('product_id', $rentalProductId);
        $options          = $optionCollection->getData();
        foreach ($options as $option) {
            $optionId             = $option['id'];
            $optionTypeCollection = $this->rentalOptionTypeFactory->create()->getCollection()->addFieldToFilter('option_id', $optionId);
            $optionTypes          = $optionTypeCollection->getData();

            return $optionTypes;
        }
    }

    /**
     * @param $price
     *
     * @return mixed
     */
    public function getFormattedPrice($price)
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper    = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        $formattedPrice = $priceHelper->currency($price, true, false);

        return $formattedPrice;
    }

    /**
     * @param $time
     *
     * @return string
     */
    public function getLocateTime($time)
    {
        return $this->_timezone->formatDateTime($time, 3, 3);
    }
    
    /**
     * @return $this|Template
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getRents()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'rental.order.history.pager'
            )->setAvailableLimit(array(5 => 5, 10 => 10, 15 => 15, 20 => 20))
                ->setShowPerPage(true)->setCollection(
                    $this->getRents()
                );
            $this->setChild('pager', $pager);
            $this->getRents()->load();
        }

        return $this;
    }

    /**
     * Get Google Map Api key
     * @return mixed
     */
    public function getGoogleApiKey()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_GOOGLE_MAP_API_KEY);
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}