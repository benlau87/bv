<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-15T20:15:44+00:00
 * File:          app/code/Xtento/ProductExport/Controller/Adminhtml/Profile.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Controller\Adminhtml;

abstract class Profile extends \Xtento\ProductExport\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Xtento\ProductExport\Helper\Entity
     */
    protected $entityHelper;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Xtento\ProductExport\Model\ProfileFactory
     */
    protected $profileFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    protected $dateFilter;

    /**
     * Profile constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Xtento\ProductExport\Helper\Module $moduleHelper
     * @param \Xtento\XtCore\Helper\Cron $cronHelper
     * @param \Xtento\ProductExport\Model\ResourceModel\Profile\CollectionFactory $profileCollectionFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     * @param \Xtento\ProductExport\Helper\Entity $entityHelper
     * @param \Xtento\ProductExport\Model\ProfileFactory $profileFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Xtento\ProductExport\Helper\Module $moduleHelper,
        \Xtento\XtCore\Helper\Cron $cronHelper,
        \Xtento\ProductExport\Model\ResourceModel\Profile\CollectionFactory $profileCollectionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Xtento\ProductExport\Helper\Entity $entityHelper,
        \Xtento\ProductExport\Model\ProfileFactory $profileFactory
    ) {
        parent::__construct($context, $moduleHelper, $cronHelper, $profileCollectionFactory, $scopeConfig);
        $this->registry = $registry;
        $this->entityHelper = $entityHelper;
        $this->escaper = $escaper;
        $this->dateFilter = $dateFilter;
        $this->profileFactory = $profileFactory;
    }

    /**
     * Check if user has enough privileges
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Xtento_ProductExport::profile');
    }

    /**
     * @param $resultPage \Magento\Backend\Model\View\Result\Page
     */
    protected function updateMenu($resultPage)
    {
        $resultPage->setActiveMenu('Xtento_ProductExport::profiles');
        $resultPage->addBreadcrumb(__('Products'), __('Products'));
        $resultPage->addBreadcrumb(__('Export Profiles'), __('Export Profiles'));
        $resultPage->getConfig()->getTitle()->prepend(__('Product Export - Profiles'));
    }
}
