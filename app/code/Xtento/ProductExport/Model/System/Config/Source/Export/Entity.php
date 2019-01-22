<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-14T15:37:35+00:00
 * File:          app/code/Xtento/ProductExport/Model/System/Config/Source/Export/Entity.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\System\Config\Source\Export;

use Magento\Framework\Option\ArrayInterface;

/**
 * @codeCoverageIgnore
 */
class Entity implements ArrayInterface
{
    /**
     * @var \Xtento\ProductExport\Model\Export
     */
    protected $exportModel;

    /**
     * Entity constructor.
     * @param \Xtento\ProductExport\Model\Export $exportModel
     */
    public function __construct(\Xtento\ProductExport\Model\Export $exportModel)
    {
        $this->exportModel = $exportModel;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->exportModel->getEntities();
    }
}
