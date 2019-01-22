<?php
namespace Rokanthemes\Themeoption\Model\Config;

class Newsletter implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('Disable')], 
            ['value' => '1', 'label' => __('Enable on Homepage')], 
            ['value' => '2', 'label' => __('Enable on All Pages')]
        ];
    }

    public function toArray()
    {
        return [
            '0' => __('Disable'), 
            '1' => __('Enable on Homepage'), 
            '2' => __('Enable on All Pages')
        ];
    }
}
