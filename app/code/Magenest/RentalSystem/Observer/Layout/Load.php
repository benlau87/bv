<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Observer\Layout;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class Load
 * @package Magenest\RentalSystem\Observer\Layout
 */
class Load implements ObserverInterface
{
    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $fullActionName = $observer->getEvent()->getFullActionName();
        /** @var  $layout \Magento\Framework\View\Layout */
        $layout = $observer->getEvent()->getLayout();
        $handler = '';
        if ($fullActionName == 'catalog_product_view') {
            $productId = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\App\RequestInterface::class)->getParam("id");
            if ($productId) {
                $rentalId = \Magento\Framework\App\ObjectManager::getInstance()
                    ->get(\Magenest\RentalSystem\Helper\Rental::class)->isRental($productId);
                if ($rentalId) {
                    $handler = 'catalog_product_view_rental';
                }
            }
        }
        if ($handler) {
            $layout->getUpdate()->addHandle($handler);
        }
    }
}