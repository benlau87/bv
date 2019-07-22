<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Model\Product\Type;

use Magento\Catalog\Model\Product\Type\AbstractType;

/**
 * Class Rental
 * @package Magenest\RentalSystem\Model\Product\Type
 */
class Rental extends AbstractType
{
    /**
     * Check if shipping is applied
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return bool
     * //     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function isVirtual($product)
    {
        if ($product->hasCustomOptions()) {
            $options = $product->getCustomOptions();
            if (!empty($options['info_buyRequest'])) {
                $check = class_exists('Magento\Framework\Serialize\Serializer\Json');
                if ($check)
                    $rentalData = json_decode($options['info_buyRequest']->getValue(), true);
                else $rentalData = unserialize($options['info_buyRequest']->getValue());
                if (@$rentalData['additional_options']['local_pickup'] == 1)
                    return true;
            }
        }

        return false;
    }

    /**
     * Delete data specific for Rental product type
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return void
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
    }

    /**
     * Check if product has options
     *
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return boolean
     */
    public function hasOptions($product)
    {
        return true;
    }
}
