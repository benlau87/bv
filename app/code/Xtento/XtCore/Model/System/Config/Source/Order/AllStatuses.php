<?php

/**
 * Product:       Xtento_XtCore (2.3.0)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2017-08-16T08:52:13+00:00
 * File:          app/code/Xtento/XtCore/Model/System/Config/Source/Order/AllStatuses.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\XtCore\Model\System\Config\Source\Order;

/**
 * Class AllStatuses
 *
 * Order Statuses source model
 *
 * @package Xtento\XtCore\Model\System\Config\Source\Order
 */
class AllStatuses extends \Magento\Sales\Model\Config\Source\Order\Status
{
    protected $_stateStatuses = false; // Get all statuses, see \Magento\Sales\Model\Config\Source\Order\Status

    /**
     * Function to just put all order status "codes" into an array.
     *
     * @return array
     */
    public function toArray()
    {
        $statuses = $this->toOptionArray();
        $statusArray = [];
        foreach ($statuses as $status) {
            array_push($statusArray, $status['value']);
        }
        return $statusArray;
    }

}
