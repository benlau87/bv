<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Controller\Adminhtml\Order;

use Magenest\RentalSystem\Controller\Adminhtml\Order as OrderController;
use Magento\Framework\Controller\ResultFactory;

class MassStatus extends OrderController
{
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $status     = (int)$this->getRequest()->getParam('status');
        $totals     = 0;

        if ($status == 1) {
            try {
                foreach ($collection as $item) {
                    /** @var \Magenest\RentalSystem\Model\RentalOrder $item */
                    if ($item->getData('status') == 0) {
                        $item->setStatus($status)->save();
//                        $toUpdate[$item->getData('rental_id')] = 0;
                        $totals++;
                    }
                }
                $this->messageManager->addSuccessMessage(__('A total of %1 rental(s) have been set as delivered.', $totals));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        if ($status == 2) {
            try {
                foreach ($collection as $item) {
                    /** @var \Magenest\RentalSystem\Model\RentalOrder $item */
                    if ($item->getData('status') == 1) {
                        $qty         = $item->getData('qty');
                        $orderItemId = $item->getData('order_item_id');
                        $rentalId    = $item->getData('rental_id');
                        $returnQty   = $item->getData('return_qty');
                        $returnQty   = isset($returnQty) ? $returnQty : 0;
                        /** @var \Magento\Sales\Model\Order\Item $orderItem */
                        $orderItem   = $this->_orderItem->create()->load($orderItemId);
                        $item->setStatus($status)->save();

                        $this->_rentalHelper->updateAvailableQty($rentalId, $orderItem->getQtyRefunded() - $returnQty);
                        $this->_rentalHelper->updateRentalProductQty($rentalId);

                        $totals++;
                    }
                }
                $this->messageManager->addSuccessMessage(__('A total of %1 rental(s) have been set as returned.', $totals));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}