<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Model\Config\Source;

/**
 * Class FirstDay
 * @package Magenest\RentalSystem\Model\Config\Source
 */
class FirstDay implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('Sunday'), 'value' => '0'],
            ['label' => __('Monday'), 'value' => '1'],
        ];
    }
}