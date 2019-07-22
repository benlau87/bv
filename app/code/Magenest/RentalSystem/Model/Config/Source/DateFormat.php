<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Model\Config\Source;

/**
 * Class DateFormat
 * @package Magenest\RentalSystem\Model\Config\Source
 */
class DateFormat implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('MM/DD'), 'value' => 'MM/DD'],
            ['label' => __('DD/MM'), 'value' => 'DD/MM'],
            ['label' => __('MM/DD/YYYY'), 'value' => 'MM/DD/YYYY'],
            ['label' => __('DD/MM/YYYY'), 'value' => 'DD/MM/YYYY'],
            ['label' => __('MM/DD/YY'), 'value' => 'MM/DD/YY'],
            ['label' => __('DD/MM/YY'), 'value' => 'DD/MM/YY'],
        ];
    }
}