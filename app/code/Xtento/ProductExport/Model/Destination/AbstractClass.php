<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-14T15:37:35+00:00
 * File:          app/code/Xtento/ProductExport/Model/Destination/AbstractClass.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Model\Destination;

abstract class AbstractClass extends \Magento\Framework\Model\AbstractModel implements DestinationInterface
{
    protected $connection;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var \Xtento\ProductExport\Model\DestinationFactory
     */
    protected $destinationFactory;

    /**
     * AbstractClass constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Xtento\ProductExport\Model\DestinationFactory $destinationFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Xtento\ProductExport\Model\DestinationFactory $destinationFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->objectManager = $objectManager;
        $this->scopeConfig = $scopeConfig;
        $this->dateTime = $dateTime;
        $this->filesystem = $filesystem;
        $this->encryptor = $encryptor;
        $this->destinationFactory = $destinationFactory;
    }
}