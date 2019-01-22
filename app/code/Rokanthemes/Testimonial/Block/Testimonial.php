<?php
/**
* Copyright © 2015 tokitheme.com. All rights reserved.

* @author Blue Sky Team <contact@tokitheme.com>
*/

namespace Rokanthemes\Testimonial\Block;

class Testimonial extends \Magento\Framework\View\Element\Template {

	protected $_template = 'Rokanthemes_Testimonial::testimonial.phtml';

	/**
	 * Testimonial Factory
	 * @var \Rokanthemes\Testimonial\Model\TestimoFactory
	 */
	protected $_testimoFactory;

	protected $_scopeConfig;
	
	protected $customerSession;

	/**
	 * [__construct description]
	 * @param \Magento\Framework\View\Element\Template\Context                $context                 [description]
	 * @param \Rokanthemes\Testimonial\Model\TestimoFactory                     $testimoFactory           [description]
	 * @param \Magento\Framework\Registry                                     $coreRegistry            [description]
	 * @param \Rokanthemes\Testimonial\Model\ResourceModel\Testimo\CollectionFactory $testimoCollectionFactory [description]
	 * @param \Magento\Customer\Model\Session $customerSession [description]
	 * @param array                                                           $data                    [description]
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Rokanthemes\Testimonial\Model\TestimoFactory $testimoFactory,
		\Rokanthemes\Testimonial\Model\ResourceModel\Testimo\CollectionFactory $testimoCollectionFactory,
		\Magento\Customer\Model\Session $customerSession,
		array $data = []
	) {
		parent::__construct($context, $data);
		$this->_testimoFactory = $testimoFactory;
		$this->_testimoCollectionFactory = $testimoCollectionFactory;
		$this->_scopeConfig = $context->getScopeConfig();
		$this->customerSession = $customerSession;
		$this->pageConfig->getTitle()->set(__('Submit Your Testimonial'));
	}
	
	public function getStoreId()
	{
		return $this->_storeManager->getStore()->getId();
	}
	
	/**
	 * @return
	 */
	public function getTestimonial() {
		$CurentstoreId = $this->_storeManager->getStore()->getId();
		$sliderCollection = $this->_testimoFactory
			->create()
			->getCollection()
			->addFieldToFilter('status', 1)
			->addFieldToFilter('store_id', array('or'=> array(
				0 => array('eq', '0'),
				1 => array('like' => '%'.$CurentstoreId.'%')
				)));
		$sliderCollection->setOrderByTestimo();
		return $sliderCollection;
	}
	
	public function getConfig($config)
	{
		return $this->_scopeConfig->getValue('testimonial/general/'.$config, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
	
	public function getIdStore()
	{
		return $this->_storeManager->getStore()->getId();
	}
	
	/**
	 * @return
	 */
	public function getMediaFolder() {
		$media_folder = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		return $media_folder;
	}

	/**
	 * @return
	 */
	protected function _toHtml() {
		$store = $this->_storeManager->getStore()->getId();

		if ($this->_scopeConfig->getValue('testimonial/general/enable_frontend', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store)) {
			return parent::_toHtml();
		}

		return '';
	}

	/**
	 * Add elements in layout
	 *
	 * @return
	 */
	protected function _prepareLayout() {
		return parent::_prepareLayout();
	}
	
	
	public function checklogin()
	{
		return $this->customerSession->isLoggedIn();
	}
}
