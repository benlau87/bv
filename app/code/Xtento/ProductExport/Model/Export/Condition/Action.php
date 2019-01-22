<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-14T15:37:35+00:00
 * File:          app/code/Xtento/ProductExport/Model/Export/Condition/Action.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Export\Condition;

class Action extends \Magento\Rule\Model\Condition\Combine
{
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * Action constructor.
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param array $data
     */
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        array $data = []
    ) {
        $this->_eventManager = $eventManager;
        parent::__construct($context, $data);
        $this->setType('Xtento\ProductExport\Model\Export\Condition\Action');
    }

    /**
     * Get new child select options
     *
     * @return array
     */
    public function getNewChildSelectOptions()
    {
        return [];
    }
}
