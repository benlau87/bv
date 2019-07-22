<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class DaysOfWeek extends Field
{
    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setStyle('width:50px;')->setName($element->getName() . '[]');

        if ($element->getValue()) {
            $values = explode(',', $element->getValue());
        } else {
            $values = [];
        }

        $sun = $element->setValue(isset($values[0]) ? $values[0] : null)->getElementHtml();
        $mon = $element->setValue(isset($values[1]) ? $values[1] : null)->getElementHtml();
        $tue = $element->setValue(isset($values[2]) ? $values[2] : null)->getElementHtml();
        $wed = $element->setValue(isset($values[3]) ? $values[3] : null)->getElementHtml();
        $thu = $element->setValue(isset($values[4]) ? $values[4] : null)->getElementHtml();
        $fri = $element->setValue(isset($values[5]) ? $values[5] : null)->getElementHtml();
        $sat = $element->setValue(isset($values[6]) ? $values[6] : null)->getElementHtml();

        return $sun .' '. $mon .' '. $tue .' '. $wed .' '. $thu .' '. $fri .' '. $sat;
    }
}