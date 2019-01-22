<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-21T11:04:47+00:00
 * File:          app/code/Xtento/ProductExport/Controller/Adminhtml/Log.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Controller\Adminhtml;

abstract class Log extends \Xtento\ProductExport\Controller\Adminhtml\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Xtento\ProductExport\Model\LogFactory
     */
    protected $logFactory;

    /**
     * @var \Xtento\ProductExport\Helper\Module
     */
    protected $moduleHelper;

    /**
     * Log constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Xtento\ProductExport\Helper\Module $moduleHelper
     * @param \Xtento\XtCore\Helper\Cron $cronHelper
     * @param \Xtento\ProductExport\Model\ResourceModel\Profile\CollectionFactory $profileCollectionFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Xtento\ProductExport\Model\LogFactory $logFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Xtento\ProductExport\Helper\Module $moduleHelper,
        \Xtento\XtCore\Helper\Cron $cronHelper,
        \Xtento\ProductExport\Model\ResourceModel\Profile\CollectionFactory $profileCollectionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Xtento\ProductExport\Model\LogFactory $logFactory
    ) {
        parent::__construct($context, $moduleHelper, $cronHelper, $profileCollectionFactory, $scopeConfig);
        $this->registry = $registry;
        $this->escaper = $escaper;
        $this->logFactory = $logFactory;
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * Check if user has enough privileges
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Xtento_ProductExport::log');
    }

    /**
     * @param $resultPage \Magento\Backend\Model\View\Result\Page
     */
    protected function updateMenu($resultPage)
    {
        $resultPage->setActiveMenu('Xtento_ProductExport::log');
        $resultPage->addBreadcrumb(__('Products'), __('Products'));
        $resultPage->addBreadcrumb(__('Execution Log'), __('Execution Log'));
        $resultPage->getConfig()->getTitle()->prepend(__('Product Export - Execution Log'));
    }

    protected function getFilesForLogId($logId, $massDownload = false)
    {
        $model = $this->logFactory->create();
        $model->load($logId);

        if (!$model->getId()) {
            if (!$massDownload) {
                $this->messageManager->addErrorMessage(__('This log entry (ID: %1) does not exist anymore.', $logId));
            }
            return false;
        }

        $filesNotFound = 0;
        $exportedFiles = [];
        $savedFiles = $model->getFiles();
        if (empty($savedFiles)) {
            if (!$massDownload) {
                $this->messageManager->addWarningMessage(
                    __('There is nothing to download. No files have been saved with this export. (Log ID: %1)', $logId)
                );
            }
            return false;
        }
        $savedFiles = explode("|", $savedFiles);

        $baseFilenames = [];
        foreach ($savedFiles as $filePath) {
            array_push($baseFilenames, basename($filePath));
        }
        $baseFilenames = array_unique($baseFilenames);

        foreach ($baseFilenames as $filename) {
            $filePath = $this->moduleHelper->getExportBkpDir() . $logId . '_' . $filename;
            $data = @file_get_contents($filePath);
            if ($data === false && !$this->getRequest()->getParam('force', false)) {
                $filesNotFound++;
                if (!$massDownload) {
                    $this->messageManager->addWarningMessage(
                        __('File not found in local backup directory: %1 (Log ID: %2)', $filePath, $logId)
                    );
                }
                if ($filesNotFound == count($baseFilenames)) {
                    return false;
                }
            }
            $exportedFiles[$filename] = $data;
        }
        if ($filesNotFound > 0 && $filesNotFound !== count($baseFilenames) && !$this->getRequest()->getParam(
                'force',
                false
            )
        ) {
            $this->messageManager->addComplexWarningMessage(
                'backendHtmlMessage',
                [
                    'html' => (string)__(
                        'One or more files of this export have been deleted from the local backup directory. Please click <a href="%1">here</a> to download the remaining existing files. (Log ID: %2)',
                        $this->getUrl('*/*/*', ['id' => $logId, 'force' => true]),
                        $logId
                    )
                ]
            );
            return false;
        }

        return $exportedFiles;
    }

    protected function deleteFilesFromFilesystem($model)
    {
        $savedFiles = $model->getFiles();
        if (empty($savedFiles)) {
            return false;
        }
        $savedFiles = explode("|", $savedFiles);

        $baseFilenames = [];
        foreach ($savedFiles as $filePath) {
            array_push($baseFilenames, basename($filePath));
        }
        $baseFilenames = array_unique($baseFilenames);

        foreach ($baseFilenames as $filename) {
            $filePath = $this->moduleHelper->getExportBkpDir() . $model->getId() . '_' . $filename;
            @unlink($filePath);
        }
        return true;
    }
}
