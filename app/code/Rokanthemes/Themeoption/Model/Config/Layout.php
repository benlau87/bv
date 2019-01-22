<?php
namespace Rokanthemes\Themeoption\Model\Config;

class Layout implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('Default')],
            ['value' => '1170', 'label' => __('1170px')],
            ['value' => '1280', 'label' => __('1280px')],
            ['value' => '1920', 'label' => __('1920px')],
            ['value' => 'full_width', 'label' => __('Full Width')]
        ];
    }

    public function toArray()
    {
        return [
            '' => __('Default'),
            '1170' => __('1170px'),
            '1280' => __('1280px'),
            '1920' => __('1920px'),
            'full_width' => __('Full Width')
        ];
    }
}
