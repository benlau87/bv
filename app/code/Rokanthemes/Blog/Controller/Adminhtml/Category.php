<?php
/**
 * Copyright © 2015 RokanThemes.com. All rights reserved.

 * @author RokanThemes Team <contact@rokanthemes.com>
 */

namespace Rokanthemes\Blog\Controller\Adminhtml;

/**
 * Admin blog category edit controller
 */
class Category extends Actions
{
	/**
	 * Form session key
	 * @var string
	 */
    protected $_formSessionKey  = 'blog_category_form_data';

    /**
     * Allowed Key
     * @var string
     */
    protected $_allowedKey      = 'Rokanthemes_Blog::category';

    /**
     * Model class name
     * @var string
     */
    protected $_modelClass      = 'Rokanthemes\Blog\Model\Category';

    /**
     * Active menu key
     * @var string
     */
    protected $_activeMenu      = 'Rokanthemes_Blog::category';

    /**
     * Status field name
     * @var string
     */
    protected $_statusField     = 'is_active';
}