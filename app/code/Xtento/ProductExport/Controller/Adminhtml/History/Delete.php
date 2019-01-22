<?php

/**
 * Product:       Xtento_ProductExport (2.6.9)
 * ID:            1eBZP1q/cM67kYGMQm8wxVjSda4Ywx7ofUEMdoI8sb8=
 * Packaged:      2018-09-11T11:23:45+00:00
 * Last Modified: 2016-04-14T15:37:35+00:00
 * File:          app/code/Xtento/ProductExport/Controller/Adminhtml/History/Delete.php
 * Copyright:     Copyright (c) 2018 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\ProductExport\Controller\Adminhtml\History;

class Delete extends \Xtento\ProductExport\Controller\Adminhtml\History
{
    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);

        $id = (int)$this->getRequest()->getParam('id');
        $model = $this->historyFactory->create();
        $model->load($id);

        if ($id && !$model->getId()) {
            $this->messageManager->addErrorMessage(__('This history entry does not exist anymore.'));
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }

        try {
            $model->delete();
            $this->messageManager->addSuccessMessage(__('History entry has been deleted successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}