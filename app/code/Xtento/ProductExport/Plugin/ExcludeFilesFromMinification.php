<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2017-11-13T17:16:28+00:00
 * File:          app/code/Xtento/ProductExport/Plugin/ExcludeFilesFromMinification.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Plugin;

use Magento\Framework\View\Asset\Minification;

class ExcludeFilesFromMinification
{
    public function aroundGetExcludes(Minification $subject, callable $proceed, $contentType)
    {
        $result = $proceed($contentType);
        if ($contentType != 'js') {
            return $result;
        }
        $result[] = 'Xtento_ProductExport/js/ace/mode-xml';
        $result[] = 'Xtento_ProductExport/js/ace/theme-eclipse';
        return $result;
    }
}