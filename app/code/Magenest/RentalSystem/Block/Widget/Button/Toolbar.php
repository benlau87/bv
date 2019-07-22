<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Block\Widget\Button;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Backend\Block\Widget\Button\ButtonList;
use Magento\Backend\Block\Widget\Button\Toolbar as ToolbarContext;

class Toolbar
{
    /**
     * @param ToolbarContext $toolbar
     * @param AbstractBlock $context
     * @param ButtonList $buttonList
     * @return array
     */
    public function beforepushButtons(
        ToolbarContext $toolbar,
        AbstractBlock $context,
        ButtonList $buttonList
    ) {
        if (!$context instanceof \Magento\Sales\Block\Adminhtml\Order\View) {
            return [$context, $buttonList];
        }

        $buttonList->update('order_edit', 'class', 'edit');
        $buttonList->update('order_invoice', 'class', 'invoice primary');
        $buttonList->update('order_invoice', 'sort_order', (count($buttonList->getItems()) + 1) * 10);

        $items = $context->getOrder()->getItems();
        foreach ($items as $item)
            if ($item->getProductType() == 'rental') {
                $buttonList->remove('order_reorder');
                break;
            }

        return [$context, $buttonList];
    }
}
