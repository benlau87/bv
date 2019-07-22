<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magenest\RentalSystem\Helper\Rental as Helper;
use Magento\CatalogInventory\Helper\Data;
use \Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;

class QuantityValidator implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * QuantityValidator constructor.
     *
     * @param LoggerInterface $logger
     * @param Helper $helper
     * @param RequestInterface $request
     * @param Registry $registry
     */
    public function __construct(
        LoggerInterface $logger,
        Helper $helper,
        RequestInterface $request,
        Registry $registry
    ) {
        $this->logger    = $logger;
        $this->helper    = $helper;
        $this->_request  = $request;
        $this->_registry = $registry;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        try {
            /* @var $quoteItem \Magento\Quote\Model\Quote\Item */
            $quoteItem = $observer->getEvent()->getItem();
            $id        = $quoteItem->getProduct()->getId();
            if (!$this->helper->isRental($id)) {

                return;
            }
            $request = $this->_request->getParams();

            if (!empty($request['additional_options'])) {

                $additionalOptions = $request['additional_options'];
                $priceErr          = false;
                $from              = @$additionalOptions['rental_from'];
                $to                = @$additionalOptions['rental_to'];
                $hours             = @$additionalOptions['rental_hours'];

                $validateDuration = $this->helper->validateDuration($id, $hours);
                $validatePrice    = $this->helper->validatePrice($id, $additionalOptions);

                $hour_diff = ceil(($to - $from) / 3600);
                if ((($hour_diff - $hours) != 0) || $validatePrice == false || $validateDuration == false)
                    $priceErr = true;

                if ($priceErr == true)
                    $quoteItem->addErrorInfo(
                        'erro_info',
                        Data::ERROR_QTY,
                        __('An error occurred when adding item to cart. Please refresh the page and try again.')
                    );
            } else if (isset($request['item_qty']) && $quoteItem->getQty() != @$request['item_qty']) {
                $this->_registry->register('update_qty' . $quoteItem->getId(), true);
            } else if ($this->_registry->registry('update_qty' . $quoteItem->getId()) != true && !empty($request) && empty($request['cart'][$quoteItem->getId()])) {
                $quoteItem->addErrorInfo(
                    'erro_info',
                    Data::ERROR_QTY,
                    __('Rent duration not selected.')
                );
            }

            $totalQuoteQty = $this->validateQty($observer);
            if (!empty($totalQuoteQty)) {
                foreach ($totalQuoteQty as $key => $value) {
                    $availableQty = $this->helper->getAvailableQty($key);
                    if ($availableQty < $value)
                        $quoteItem->addErrorInfo(
                            'erro_info',
                            Data::ERROR_QTY,
                            __('We don\'t have as many ' . $quoteItem->getName() . ' as you requested. Maximum qty you can rent is '
                                . $availableQty)
                        );
                }
            }

        } catch (\Exception $exception) {
            $this->logger->critical($exception);
        }
    }

    /**
     * @param Observer $observer
     *
     * @return array
     */
    public function validateQty($observer)
    {
        $quoteItems = $observer->getEvent()->getItem()->getquote()->getAllItems();
        $rentalQty  = [];

        /* @var $quoteItem \Magento\Quote\Model\Quote\Item */
        foreach ($quoteItems as $quoteItem) {
            $productType = $quoteItem->getProductType();
            if ($productType == 'rental') {
                $productId = $quoteItem->getProduct()->getId();
                if (@$rentalQty[$productId] > 0)
                    $rentalQty[$productId] += $quoteItem->getQty();
                else $rentalQty[$productId] = $quoteItem->getQty();
            }
        }

        return $rentalQty;
    }
}