<?php
/**
 * Copyright © 2015 RokanThemes.com. All rights reserved.

 * @author RokanThemes Team <contact@rokanthemes.com>
 */

namespace Rokanthemes\Blog\Controller\Post;

/**
 * Blog post view
 */
class View extends \Magento\Framework\App\Action\Action
{
    /**
     * View Blog post action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $post = $this->_objectManager->create('Rokanthemes\Blog\Model\Post')->load($id);
        if (!$post->getId()) {
            $this->_forward('index', 'noroute', 'cms');
            return;
        }

        $this->_objectManager->get('\Magento\Framework\Registry')->register('current_blog_post', $post);

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

}
