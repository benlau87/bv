<?php
/**
* Copyright © 2015 tokitheme.com. All rights reserved.

* @author Blue Sky Team <contact@tokitheme.com>
*/

namespace Rokanthemes\Testimonial\Controller\Adminhtml\Testimo;

class Delete extends \Rokanthemes\Testimonial\Controller\Adminhtml\Testimo
{
    public function execute()
    {
        $testimoId = $this->getRequest()->getParam('testimo_id');
        try {
            $locator = $this->_objectManager->create('Rokanthemes\Testimonial\Model\Testimo')->load($testimoId);
            $locator->delete();
            $this->messageManager->addSuccess(
                __('Delete successfully !')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}
