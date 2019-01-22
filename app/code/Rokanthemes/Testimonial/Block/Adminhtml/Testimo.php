<?php
/**
* Copyright © 2015 tokitheme.com. All rights reserved.

* @author Blue Sky Team <contact@tokitheme.com>
*/

namespace Rokanthemes\Testimonial\Block\Adminhtml;

class Testimo extends \Magento\Backend\Block\Widget\Grid\Container {
	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function _construct() {

		$this->_controller = 'adminhtml_testimo';
		$this->_blockGroup = 'Rokanthemes_Testimonial';
		$this->_headerText = __('Testimonials');
		$this->_addButtonLabel = __('Add New Testimonial');
		parent::_construct();
	}
}
