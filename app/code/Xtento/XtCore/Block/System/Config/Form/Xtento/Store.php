<?php

/**
 * Product:       Xtento_XtCore (2.3.0)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2018-09-05T16:34:14+00:00
 * File:          app/code/Xtento/XtCore/Block/System/Config/Form/Xtento/Store.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\XtCore\Block\System\Config\Form\Xtento;

class Store extends \Magento\Config\Block\System\Config\Form\Field
{
    /*
     * XTENTO Store
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $extStoreUrl = 'https://www.xtento.com/magento-2-extensions.html?extensionstore=true';
        $html = <<<EOT
<script>
requirejs(['prototype'], function() {
    if (typeof $$(".save")[0] !== 'undefined') {
        $$(".save")[0].down('span').innerHTML = 'Open the XTENTO Extension Store in a new window'
        $$(".save")[0].setAttribute('onclick', "window.open('{$extStoreUrl}', '_blank'); return false;");
    }
});
</script>
<iframe src="{$extStoreUrl}" scrolling="auto"
style="width: 100%; height: 900px !important; display: block; border: 1px solid #ccc;"></iframe>
EOT;
        return $html;
    }
}
