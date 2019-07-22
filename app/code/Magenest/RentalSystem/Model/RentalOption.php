<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Registration
 * @package Magenest\RentalSystem\Model
 */
class RentalOption extends AbstractModel
{
    const TYPE_FIXED = 'fixed';

    const TYPE_PER_HOUR = 'per_hour';

    const TYPE_PER_DAY = 'per_day';
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenest\RentalSystem\Model\ResourceModel\RentalOption');
    }
}