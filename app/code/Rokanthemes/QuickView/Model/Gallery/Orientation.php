<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 */
namespace Rokanthemes\QuickView\Model\Gallery;

class Orientation implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'horizontal', 'label' => __('Horizontal')], ['value' => 'vertical', 'label' => __('Vertical')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['horizontal' => __('Horizontal'), 'vertical' => __('Vertical')];
    }
}
