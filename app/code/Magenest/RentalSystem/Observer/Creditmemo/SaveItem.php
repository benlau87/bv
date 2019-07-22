<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Observer\Creditmemo;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\ItemFactory;
use Magenest\RentalSystem\Model\RentalOrderFactory;
use Magenest\RentalSystem\Helper\Rental as Helper;

class SaveItem implements ObserverInterface
{
    /**
     * @var ItemFactory
     */
    protected $orderItemFactory;

    /**
     * @var RentalOrderFactory
     */
    protected $rentalOrderFactory;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * SaveItem constructor.
     *
     * @param ItemFactory $orderItemFactory
     * @param RentalOrderFactory $rentalOrderFactory
     * @param Helper $helper
     */
    public function __construct(
        ItemFactory $orderItemFactory,
        RentalOrderFactory $rentalOrderFactory,
        Helper $helper
    ) {
        $this->rentalOrderFactory = $rentalOrderFactory;
        $this->orderItemFactory   = $orderItemFactory;
        $this->helper             = $helper;
    }

    /**
     * @param Observer $observer
     *
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order\Creditmemo\Item $creditMemoItem */
        $creditMemoItem = $observer->getEvent()->getData('creditmemo_item');

        $orderItemId = $creditMemoItem->getOrderItemId();
        $rentalItem  = $this->rentalOrderFactory->create()->loadByOrderItemId($orderItemId);

        if ($rentalItem->getId()) {
            if ($rentalItem->getData('status') == 2) {
                $qty      = $creditMemoItem->getQty();
                $rentalId = $rentalItem->getData('rental_id');
                if (@$creditMemoItem->getData('back_to_stock') == false) {
                    $this->helper->updateRentalProductQty($rentalId, $qty * -1);
                    $this->helper->updateAvailableQty($rentalId, $qty);
                } else
                    $this->helper->updateRentalProductQty($rentalId);

            } else {
                $qty           = $creditMemoItem->getQty();
                $rentalOrderId = $rentalItem->getId();
                if (@$creditMemoItem->getData('back_to_stock') == false) {
                    $this->helper->updateReturnQty($rentalOrderId, 0);
                } else $this->helper->updateReturnQty($rentalOrderId, $qty);
            }

        }
    }
}