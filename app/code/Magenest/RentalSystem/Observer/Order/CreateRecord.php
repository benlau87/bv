<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Observer\Order;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\ProductFactory;
use Magenest\RentalSystem\Model\Rental;
use Magenest\RentalSystem\Model\RentalFactory;
use Magenest\RentalSystem\Model\RentalOrderFactory;
use Magenest\RentalSystem\Helper\Rental as Helper;

class CreateRecord implements ObserverInterface
{
    const XML_PATH_CODE_PATTERN = 'rental_system/general/pattern_code';

    /**
     * @var Helper
     */
    protected $_helper;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var ProductFactory
     */
    protected $_productFactory;

    /**
     * @var RentalFactory
     */
    protected $_rentalFactory;

    /**
     * @var RentalOrderFactory
     */
    protected $_rentalOrderFactory;

    /**
     * CreateRecord constructor.
     *
     * @param Helper $helper
     * @param ScopeConfigInterface $scopeConfig
     * @param ProductFactory $productFactory
     * @param RentalFactory $rentalFactory
     * @param RentalOrderFactory $rentalOrderFactory
     */
    public function __construct(
        Helper $helper,
        ScopeConfigInterface $scopeConfig,
        ProductFactory $productFactory,
        RentalFactory $rentalFactory,
        RentalOrderFactory $rentalOrderFactory
    ) {
        $this->_helper             = $helper;
        $this->_scopeConfig        = $scopeConfig;
        $this->_productFactory     = $productFactory;
        $this->_rentalFactory      = $rentalFactory;
        $this->_rentalOrderFactory = $rentalOrderFactory;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order\Item $orderItem */
        $orderItem = $observer->getEvent()->getItem();

        if (!$orderItem->getId()) {
            //order not saved in the database
            return $this;
        }

        if ($orderItem->getProductType() != Rental::PRODUCT_TYPE) {
            return $this;
        }

        $product = $orderItem->getProduct();
        if ($product->getTypeId() == Rental::PRODUCT_TYPE) {
            $order = $orderItem->getOrder();
            if ($order->hasInvoices() && $orderItem->getQtyInvoiced() > 0) {
                /** @var \Magenest\RentalSystem\Model\RentalOrder $model */
                $model      = $this->_rentalOrderFactory->create()->loadByOrderItemId($orderItem->getId());
                $buyRequest = $orderItem->getBuyRequest()->toArray();
                $options    = $buyRequest['additional_options'];
                $fromStamp  = @$options['rental_from'];
                $toStamp    = @$options['rental_to'];

                if (@$options['has_time'] == 1) {
                    $from = date('Y-m-d H:i', $fromStamp);
                    $to   = date('Y-m-d H:i', $toStamp);
                } else {
                    $from = date('Y-m-d', $fromStamp);
                    $to   = date('Y-m-d', $toStamp);
                }

                /** @var \Magenest\RentalSystem\Model\Rental $rentalModel */
                $rentalModel = $this->_rentalFactory->create()->loadByProductId($product->getId());
                $address     = $order->getShippingAddress() ? $order->getShippingAddress() : $order->getBillingAddress();
                $status      = !empty($model->getData('status')) ? $model->getData('status') : 0;
                $code        = !empty($model->getData('code')) ? $model->getData('code') : $this->generateCode();
                $information = " ";
                if (array_key_exists('options', $options)) {
                    $information = $this->_helper->decodeOptions($options['options']);
                }
                $data = [
                    'order_item_id'      => $orderItem->getId(),
                    'order_increment_id' => $order->getIncrementId(),
                    'order_id'           => $order->getId(),
                    'price'              => $orderItem->getBasePrice(),
                    'start_time'         => $from,
                    'end_time'           => $to,
                    'code'               => $code,
                    'qty'                => $orderItem->getQtyInvoiced(),
                    'status'             => $status,
                    'title'              => $product->getName(),
                    'rental_id'          => $rentalModel->getId(),
                    'information'        => $information,
                    'type'               => $rentalModel->getData('type'),
                    'customer_id'        => $order->getCustomerId(),
                    'customer_name'      => $order->getCustomerName(),
                    'customer_email'     => $order->getCustomerEmail(),
                    'customer_address'   => $this->getAddressStr($address),
                ];

                $model->addData($data);
                if (!$model->getId()) {
                    $productId        = $orderItem->getProductOptions()['info_buyRequest']['product'];
                    $rentalModel      = $this->_rentalFactory->create()->loadByProductId($productId);
                    $timeRented       = $rentalModel->getData('time_rented');
                    $updateTimeRented = $timeRented + $orderItem->getQtyInvoiced();
                    $rentalModel->setData('time_rented', $updateTimeRented);
                    $rentalModel->save();
                    $this->_helper->sendReceipt($data);
                }

                $model->save();

                $this->_helper->updateAvailableQty($rentalModel->getId());
            }
        }

        return $this;
    }

    /**
     * Get Options
     *
     * @param array $options
     *
     * @return false|string
     */
    protected function getOptionStr($options)
    {
        $check = class_exists('Magento\Framework\Serialize\Serializer\Json');
        if ($check) {
            $data = json_encode($options);
        } else {
            $data = serialize($options);
        }

        return $data;
    }

    /**
     * Get address
     *
     * @param \Magento\Sales\Model\Order\Address $address
     *
     * @return string
     */
    protected function getAddressStr($address)
    {
        $streetArr = $address->getStreet();
        $line      = '';
        foreach ($streetArr as $street) {
            $line = $line . $street . ' ';
        }

        return $line . ' ' . $address->getRegion() . ' ' . $address->getCity() . ', ' . $address->getPostcode();
    }

    /**
     * Generate code
     * @return mixed|string|string[]|null
     */
    public function generateCode()
    {
        $gen_arr = [];
        $pattern = $this->_scopeConfig->getValue(self::XML_PATH_CODE_PATTERN);
        if (!$pattern) {
            $pattern = '[A2][N1][A2]Magenest[N1][A1]';
        }

        preg_match_all("/\[[AN][.*\d]*\]/", $pattern, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $delegate = substr($match [0], 1, 1);
            $length   = substr($match [0], 2, strlen($match [0]) - 3);
            $gen      = '';
            if ($delegate == 'A') {
                $gen = $this->generateString($length);
            } elseif ($delegate == 'N') {
                $gen = $this->generateNum($length);
            }

            $gen_arr [] = $gen;
        }
        foreach ($gen_arr as $g) {
            $pattern = preg_replace('/\[[AN][.*\d]*\]/', $g, $pattern, 1);
        }

        return $pattern;
    }

    /**
     * Generate String
     *
     * @param $length
     *
     * @return string
     */
    public function generateString($length)
    {
        if ($length == 0 || $length == null || $length == '') {
            $length = 5;
        }
        $string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $rand   = '';
        for ($i = 0; $i < $length; $i++) {
            $rand .= $string[rand(0, 51)];
        }

        return $rand;
    }

    /**
     * Generate Number
     *
     * @param $length
     *
     * @return string
     */
    public function generateNum($length)
    {
        if ($length == 0 || $length == null || $length == '') {
            $length = 5;
        }
        $number = "0123456789";
        $rand   = '';
        for ($i = 0; $i < $length; $i++) {
            $rand .= $number[rand(0, 9)];
        }

        return $rand;
    }
}