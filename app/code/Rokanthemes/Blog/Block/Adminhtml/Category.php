<?php
/**
 * Copyright © 2015 RokanThemes.com. All rights reserved.

 * @author RokanThemes Team <contact@rokanthemes.com>
 */

namespace Rokanthemes\Blog\Block\Adminhtml;

/**
 * Admin blog category
 */
class Category extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml';
        $this->_blockGroup = 'Rokanthemes_Blog';
        $this->_headerText = __('Category');
        $this->_addButtonLabel = __('Add New Category');
        parent::_construct();
    }
}
