<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 */
namespace Rokanthemes\PriceCountdown\Model\Config\Source;

class Heading implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
	public function toOptionArray()
    {
        return [['value' => 'showall', 'label'=> __('Show in catalog/products pages')],
            ['value' => 'listpage', 'label'=> __('Show in catalog page')],
            ['value' => 'viewpage', 'label'=> __('Show in product page')],
            ['value' => 'hideall', 'label'=> __('Hide in all pages')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['showall' => __('Show in catalog/products pages'), 'listpage' => __('Show in catalog page'), 'viewpage' => __('Show in product page'), 'hideall' => __('Hide in all pages')];
    }
}