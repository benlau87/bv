<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Helper;

use Magento\Sales\Helper\Reorder as ReorderHelper;

class Reorder extends ReorderHelper
{
    /**
     * Check is it possible to reorder
     * Disable reorder if contains rental product
     *
     * @param int $orderId
     *
     * @return bool
     */
    public function canReorder($orderId)
    {
        $order = $this->orderRepository->get($orderId);
        $items = $order->getItems();
        foreach ($items as $item) {
            if ($item->getProductType() == 'rental')
                return false;
        }
        if (!$this->isAllowed($order->getStore())) {
            return false;
        }
        if ($this->customerSession->isLoggedIn()) {
            return $order->canReorder();
        } else {
            return true;
        }
    }
}