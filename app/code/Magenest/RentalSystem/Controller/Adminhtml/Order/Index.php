<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Controller\Adminhtml\Order;

use Magenest\RentalSystem\Controller\Adminhtml\Order as OrderController;

/**
 * Class Index
 * @package Magenest\RentalSystem\Controller\Adminhtml\Order
 */
class Index extends OrderController
{
    /**
     * execute the action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->_setPageData();

        return $this->getResultPage();
    }
}

