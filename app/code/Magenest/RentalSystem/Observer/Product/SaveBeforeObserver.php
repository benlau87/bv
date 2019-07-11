<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Observer\Product;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Action\Context;
use Magenest\RentalSystem\Model\Rental;
use Magento\Framework\Exception\StateException;

class SaveBeforeObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var Context
     */
    protected $context;

    /**
     * SaveBeforeObserver constructor.
     *
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->context        = $context;
        $this->_request       = $context->getRequest();
        $this->messageManager = $context->getMessageManager();
    }

    /**
     * @param Observer $observer
     *
     * @throws StateException
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product       = $observer->getEvent()->getProduct();
        $productTypeId = $product->getTypeId();
        $params        = $this->_request->getParams();
        if (!empty($params['rental']) && $productTypeId == Rental::PRODUCT_TYPE) {
            $basePrice = @$params['rental']['row'][0];
            if (!empty($basePrice['base_price'])) {
//                $params['product']['price'] = $basePrice['base_price'];
//                $this->validatePeriod($basePrice['base_period']);

                if (!empty($basePrice['additional_period']) && !empty($basePrice['additional_price'])){
//                    $this->validatePeriod($basePrice['additional_period']);
                }
            }
        }

        return;
    }

    /**
     * @param $period
     *
     * @throws StateException
     */
    protected function validatePeriod($period)
    {
        $length       = strlen($period);
        $timeType     = substr($period, -1);
        $timeMatch    = preg_replace('/(d|h|w|D|H|W)/', '', $timeType);
        $timeDuration = substr($period, 0, $length - 1);
        if (is_numeric($timeDuration) && (int)$timeDuration > 0 && empty($timeMatch)) {
            return;
        } else throw new StateException(__('Please use the correct period format number+time type (E.g. 2d, 5h, 1w)'));

    }
}