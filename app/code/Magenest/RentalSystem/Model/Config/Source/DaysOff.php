<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class DaysOff implements ArrayInterface
{
    public function toOptionArray()
    {
        $result  = [
            [
                'value' => 1,
                'label' => __('Monday')
            ],
            [
                'value' => 2,
                'label' => __('Tuesday')
            ],
            [
                'value' => 3,
                'label' => __('Wednesday')
            ],
            [
                'value' => 4,
                'label' => __('Thursday')
            ],
            [
                'value' => 5,
                'label' => __('Friday')
            ],
            [
                'value' => 6,
                'label' => __('Saturday')
            ],
            [
                'value' => 0,
                'label' => __('Sunday')
            ],
            [
                'value' => 7,
                'label' => __('No day off')
            ]
        ];
        return $result;
    }
}