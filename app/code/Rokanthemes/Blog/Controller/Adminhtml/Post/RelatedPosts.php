<?php
/**
 * Copyright © 2015 RokanThemes.com. All rights reserved.

 * @author RokanThemes Team <contact@rokanthemes.com>
 */

namespace Rokanthemes\Blog\Controller\Adminhtml\Post;

/**
 * Blog post related posts controller
 */
class RelatedPosts extends \Rokanthemes\Blog\Controller\Adminhtml\Post
{
    /**
     * View related posts action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
	public function execute()
    {
        $model = $this->_getModel();
        $this->_getRegistry()->register('current_model', $model);

        $this->_view->loadLayout()
            ->getLayout()
            ->getBlock('blog.post.edit.tab.relatedposts')
            ->setPostsRelated($this->getRequest()->getPost('posts_related', null));
 
        $this->_view->renderLayout();
    }
}
