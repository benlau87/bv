<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-14T15:37:35+00:00
 * File:          app/code/Xtento/ProductExport/Block/Adminhtml/Profile/Grid/Renderer/Configuration.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Block\Adminhtml\Profile\Grid\Renderer;

class Configuration extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Render profile configuration
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $configuration = [];
        $configuration['Cronjob Export'] = ($row->getCronjobEnabled()) ? __('Enabled') : __('Disabled');
        $configuration['Event Export'] = ($row->getEventObservers() !== '') ? __('Enabled') : __('Disabled');
        if (!empty($configuration)) {
            $configurationHtml = '';
            foreach ($configuration as $key => $value) {
                $configurationHtml .= __($key).': <i>'.$value.'</i><br/>';
            }
            return $configurationHtml;
        } else {
            return '---';
        }
    }
}
