<?php
/**
* Copyright © 2015 tokitheme.com. All rights reserved.

* @author Blue Sky Team <contact@tokitheme.com>
*/

namespace Rokanthemes\Testimonial\Block\Adminhtml\Testimo\Helper\Renderer;
class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer {
	/**
	 * Store manager
	 *
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;

	protected $_testimoFactory;

	/**
	 * Registry object
	 * @var \Magento\Framework\Registry
	 */
	protected $_coreRegistry;

	/**
	 * @param \Magento\Backend\Block\Context $context
	 * @param array $data
	 */
	public function __construct(
		\Magento\Backend\Block\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Rokanthemes\Testimonial\Model\TestimoFactory $testimoFactory,
		\Magento\Framework\Registry $coreRegistry,
		array $data = []
	) {
		parent::__construct($context, $data);
		$this->_storeManager = $storeManager;
		$this->_testimoFactory = $testimoFactory;
		$this->_coreRegistry = $coreRegistry;
	}

	/**
	 * Render action
	 *
	 * @param \Magento\Framework\Object $row
	 * @return string
	 */
	public function render(\Magento\Framework\DataObject $row) {
		$storeViewId = $this->getRequest()->getParam('store');
		$testimo = $this->_testimoFactory->create()->setStoreViewId($storeViewId)->load($row->getId());
		$srcImage = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $testimo->getAvatar();
		return '<image width="150" height="50" src ="' . $srcImage . '" alt="' . $testimo->getAvatar() . '" >';
	}
}