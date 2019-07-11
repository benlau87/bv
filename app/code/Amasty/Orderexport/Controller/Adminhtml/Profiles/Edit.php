<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Orderexport
 */

namespace Amasty\Orderexport\Controller\Adminhtml\Profiles;

class Edit extends \Amasty\Orderexport\Controller\Adminhtml\Profiles
{

    public function execute()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Amasty\Orderexport\Model\Profiles');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('amasty_orderexport/*');

                return;
            }
        }
        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('current_amasty_orderexport', $model);
        $this->_initAction();

        // set title and breadcrumbs
        $title      = $id ? __('Edit Amasty Efficient Order Export Profile') : __('New Profile');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(__('System'), __('System'))
                   ->addBreadcrumb(__('Manage Profiles'), __('Manage Profiles'));
        if (!empty($title)) {
            $resultPage->addBreadcrumb($title, $title);
        }
        $resultPage->getConfig()->getTitle()->prepend(__('Profiles'));
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getName() : __('New Profile'));

        $this->_view->renderLayout();
    }
}
