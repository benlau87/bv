<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class DaysOff extends Field
{
    /**
     * @param AbstractElement $element
     *
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $_options = [
            0  => __('12 a.m.'),
            1  => __('1 a.m.'),
            2  => __('2 a.m.'),
            3  => __('3 a.m.'),
            4  => __('4 a.m.'),
            5  => __('5 a.m.'),
            6  => __('6 a.m.'),
            7  => __('7 a.m.'),
            8  => __('8 a.m.'),
            9  => __('9 a.m.'),
            10 => __('10 a.m.'),
            11 => __('11 a.m.'),
            12 => __('12 p.m.'),
            13 => __('1 p.m.'),
            14 => __('2 p.m.'),
            15 => __('3 p.m.'),
            16 => __('4 p.m.'),
            17 => __('5 p.m.'),
            18 => __('6 p.m.'),
            19 => __('7 p.m.'),
            20 => __('8 p.m.'),
            21 => __('9 p.m.'),
            22 => __('10 p.m.'),
            23 => __('11 p.m.')
        ];

        $element->setValues($_options)->setClass('select-date')->setName($element->getName() . '[]');
        if ($element->getValue()) {
            $values = explode(',', $element->getValue());
        } else {
            $values = [];
        }

        $_parts   = [];
        $_parts[] = $element->setValue(isset($values[0]) ? $values[0] : null)->getElementHtml();
        $_parts[] = $element->setValue(isset($values[1]) ? $values[1] : null)->getElementHtml();

        return implode(' <span>:</span> ', $_parts);
    }
}