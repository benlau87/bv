<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class RentalOrder extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magenest_rental_order', 'id');
    }
}

