<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magenest\RentalSystem\Model\RentalFactory;
use Magenest\RentalSystem\Model\RentalPriceFactory;
use Magenest\RentalSystem\Model\RentalOptionFactory;
use Magenest\RentalSystem\Model\RentalOptionTypeFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Cart implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var ProductRepository
     */
    protected $_productRepository;

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
     * @var TimezoneInterface
     */
    protected $_timezone;

    /**
     * Cart constructor.
     *
     * @param LoggerInterface $logger
     * @param RequestInterface $request
     * @param ScopeConfigInterface $scopeConfig
     * @param RentalFactory $rentalFactory
     * @param RentalPriceFactory $rentalPriceFactory
     * @param RentalOptionFactory $rentalOptionFactory
     * @param RentalOptionTypeFactory $rentalOptionTypeFactory
     * @param ProductRepository $productRepository
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        LoggerInterface $logger,
        RequestInterface $request,
        ScopeConfigInterface $scopeConfig,
        RentalFactory $rentalFactory,
        RentalPriceFactory $rentalPriceFactory,
        RentalOptionFactory $rentalOptionFactory,
        RentalOptionTypeFactory $rentalOptionTypeFactory,
        ProductRepository $productRepository,
        TimezoneInterface $timezone
    ) {
        $this->_logger                  = $logger;
        $this->_request                 = $request;
        $this->_scopeConfig             = $scopeConfig;
        $this->_productRepository       = $productRepository;
        $this->_rentalFactory           = $rentalFactory;
        $this->_rentalPriceFactory      = $rentalPriceFactory;
        $this->_rentalOptionFactory     = $rentalOptionFactory;
        $this->_rentalOptionTypeFactory = $rentalOptionTypeFactory;
        $this->_timezone                = $timezone;
    }

    public function execute(Observer $observer)
    {
        try {
            /** @var \Magento\Quote\Model\Quote\Item $item */
            $item = $observer->getEvent()->getQuoteItem();
            /** @var \Magento\Catalog\Model\Product $product */
            $product     = $item->getProduct();
            $productId   = $product->getId();
            $data        = $this->_request->getParams();
            $productType = $this->_productRepository->getById($productId)->getTypeId();
            if ($productType == 'rental') {
                if (!empty($data['additional_options'])) {
                    $options           = $data['additional_options'];
                    $additionalOptions = [];
                    if (!empty($options['rental_price'])) {
                        $rentalOptions = @$options['options'];
                        if (!empty($rentalOptions)) {
                            foreach ($rentalOptions as $rentalOption) {
                                if (!empty($rentalOption)) {
                                    $optionData = explode("_", $rentalOption);
                                    $optionId   = @$optionData[2];
                                    $typeId     = @$optionData[1];

                                    $optionTitle = $this->_rentalOptionFactory->create()->load($optionId)->getData('option_title');
                                    $typeTitle   = $this->_rentalOptionTypeFactory->create()->load($typeId)->getData('option_title');

                                    if ($optionTitle && $typeTitle)
                                        $additionalOptions[] = [
                                            'label' => $optionTitle,
                                            'value' => $typeTitle
                                        ];
                                }
                            }
                        }
                        $item->setOriginalCustomPrice($options['rental_price']);
                    }

                    if (isset($options['local_pickup'])) {
                        $item->setWeight(null);
                        $item->setIsVirtual(1);
                    }

                    $fromStamp = @$options['rental_from'];
                    $toStamp   = @$options['rental_to'];

                    $from = $this->_timezone->formatDateTime(date('Y-m-d H:i', $fromStamp), 3, 3);
                    $to   = $this->_timezone->formatDateTime(date('Y-m-d H:i', $toStamp), 3, 3);

                    $additionalOptions[] = [
                        'label' => __("From"),
                        'value' => $from
                    ];

                    $additionalOptions[] = [
                        'label' => __("To"),
                        'value' => $to
                    ];

                    $check = class_exists('Magento\Framework\Serialize\Serializer\Json');
                    if ($check) {
                        $item->addOption(array(
                            'code'  => 'additional_options',
                            'value' => json_encode($additionalOptions)
                        ));
                    } else {
                        $item->addOption(array(
                            'code'  => 'additional_options',
                            'value' => serialize($additionalOptions)
                        ));
                    }
                }
            }
        } catch (\Exception $exception) {
            $this->_logger->critical($exception);
        }
    }
}