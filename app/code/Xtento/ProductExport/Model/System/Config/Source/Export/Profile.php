<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-20T14:02:54+00:00
 * File:          app/code/Xtento/ProductExport/Model/System/Config/Source/Export/Profile.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\System\Config\Source\Export;

class Profile
{
    /**
     * @var \Xtento\ProductExport\Model\ResourceModel\Profile\CollectionFactory
     */
    protected $profileCollectionFactory;

    /**
     * @param \Xtento\ProductExport\Model\ResourceModel\Profile\CollectionFactory $profileCollectionFactory
     */
    public function __construct(
        \Xtento\ProductExport\Model\ResourceModel\Profile\CollectionFactory $profileCollectionFactory
    ) {
        $this->profileCollectionFactory = $profileCollectionFactory;
    }

    /**
     * @param bool $all
     * @param bool $entity
     *
     * @return array
     */
    public function toOptionArray($all = false, $entity = false)
    {
        $profileCollection = $this->profileCollectionFactory->create();
        if (!$all) {
            $profileCollection->addFieldToFilter('enabled', 1);
            $profileCollection->addFieldToFilter('manual_export_enabled', 1);
        }
        if ($entity) {
            $profileCollection->addFieldToFilter('entity', $entity);
        }
        $profileCollection->getSelect()->order('entity ASC');
        $returnArray = [];
        foreach ($profileCollection as $profile) {
            $returnArray[] = [
                'profile' => $profile,
                'value' => $profile->getId(),
                'label' => $profile->getName(),
                'entity' => $profile->getEntity(),
            ];
        }
        if (empty($returnArray)) {
            $returnArray[] = [
                'profile' => new \Magento\Framework\DataObject(),
                'value' => '',
                'label' => __(
                    'No profiles available. Add and enable export profiles for the %1 entity first.',
                    $entity
                ),
                'entity' => '',
            ];
        }
        return $returnArray;
    }
}
