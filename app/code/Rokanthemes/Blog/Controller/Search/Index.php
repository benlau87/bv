<?php
/**
 * Copyright © 2015 RokanThemes.com. All rights reserved.

 * @author RokanThemes Team <contact@rokanthemes.com>
 */
namespace Rokanthemes\Blog\Controller\Search;

/**
 * Blog search results view
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * View blog search results action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

}
