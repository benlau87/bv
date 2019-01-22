<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2017-08-24T15:49:47+00:00
 * File:          app/code/Xtento/ProductExport/Block/Adminhtml/Widget/Grid/Serializer.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Block\Adminhtml\Widget\Grid;

class Serializer extends \Magento\Backend\Block\Widget\Grid\Serializer
{
    protected function _afterToHtml($html)
    {
        $newJs = <<<EOT
serializerController.prototype.rowClick = function (grid, event) {
    if (typeof Event.findElement(event, 'a') == 'undefined') { // Dont call the checkbox method if the link or action column is clicked
        var trElement = Event.findElement(event, 'tr');
        var isInput   = Event.element(event).tagName == 'INPUT';
        if(trElement){
            var checkbox = Element.select(trElement, 'input');
            if(checkbox[0] && !checkbox[0].disabled){
                var checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                this.grid.setCheckboxChecked(checkbox[0], checked);
            }
        }
        this.getOldCallback('row_click')(grid, event);
    }
};
new serializerController
EOT;
        $parentHtml = parent::_afterToHtml($html);
        $newHtml = str_replace('new serializerController', $newJs, $parentHtml);
        return $newHtml;
    }
}