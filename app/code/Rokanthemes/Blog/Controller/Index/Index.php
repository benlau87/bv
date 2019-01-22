<?php
/**
 * Copyright © 2015 RokanThemes.com. All rights reserved.

 * @author RokanThemes Team <contact@rokanthemes.com>
 */
namespace Rokanthemes\Blog\Controller\Index;

/**
 * Blog home page view
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * View blog homepage action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

}
