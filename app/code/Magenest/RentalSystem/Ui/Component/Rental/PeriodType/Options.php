<?php
/**
 * Created by PhpStorm.
 * User: ducanh
 * Date: 14/02/2019
 * Time: 13:17
 */

namespace Magenest\RentalSystem\Ui\Component\Rental\PeriodType;

class Options implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['label' => __('Hour'), 'value' => 'h'],
            ['label' => __('Day'),  'value' => 'd'],
            ['label' => __('Week'), 'value' => 'w'],
        ];
    }
}