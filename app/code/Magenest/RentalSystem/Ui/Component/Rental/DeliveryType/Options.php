<?php
/**
 * Created by PhpStorm.
 * User: ducanh
 * Date: 23/01/2019
 * Time: 08:33
 */

namespace Magenest\RentalSystem\Ui\Component\Rental\DeliveryType;

class Options implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['label' => __('Shipping'), 'value' => 'shipping'],
            ['label' => __('Local Pickup'), 'value' => 'local_pickup'],
        ];
    }
}