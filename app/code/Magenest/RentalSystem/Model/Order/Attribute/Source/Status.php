<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Model\Order\Attribute\Source;

use Magento\Catalog\Model\Product\Attribute\Source\Status as SourceStatus;

class Status extends SourceStatus
{
    public static function getOptionArray()
    {
        return [
            0 => __('Pending'),
            1 => __('Delivered'),
            2 => __('Returned')
        ];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }
}