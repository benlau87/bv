<?php
namespace Rokanthemes\Themeoption\Model\Config;

class Fontfamily implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'custom', 'label' => __('Select Custom Fonts')], 
            ['value' => 'google', 'label' => __('Select Google Fonts')]
        ];
    }

    public function toArray()
    {
        return [
            'custom' => __('Select Custom Fonts'), 
            'google' => __('Select Google Fonts')
        ];
    }
}
