<?php

/**
 * Product:       Xtento_XtCore (2.3.0)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2017-08-16T08:52:13+00:00
 * File:          app/code/Xtento/XtCore/Model/System/Config/Source/Feed/Updates.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\XtCore\Model\System\Config\Source\Feed;

class Updates implements \Magento\Framework\Option\ArrayInterface
{
    const TYPE_NEW_RELEASE = 'NEW_RELEASE';
    const TYPE_UPDATED = 'UPDATE';
    const TYPE_PROMOTIONS = 'PROMOTIONS';
    const TYPE_OTHERINFO = 'OTHER_INFO';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::TYPE_UPDATED, 'label' => __('Updates for installed extensions')],
            ['value' => self::TYPE_NEW_RELEASE, 'label' => __('New Extensions')],
            ['value' => self::TYPE_PROMOTIONS, 'label' => __('Discounts/Promotions')],
            ['value' => self::TYPE_OTHERINFO, 'label' => __('Other information')]
        ];
    }
}
