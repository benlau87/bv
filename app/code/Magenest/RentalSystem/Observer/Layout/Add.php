<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Observer\Layout;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class Add implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * Add constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request
    ){
        $this->_request = $request;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getProduct();
        if ($product->getTypeId() == 'rental') {
            $product->setHasOptions(true);
        }
    }
}