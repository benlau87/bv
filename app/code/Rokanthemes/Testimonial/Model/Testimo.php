<?php
/**
* Copyright © 2015 tokitheme.com. All rights reserved.

* @author Blue Sky Team <contact@tokitheme.com>
*/

namespace Rokanthemes\Testimonial\Model;

class Testimo extends \Magento\Framework\Model\AbstractModel {
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 2;
	const BASE_MEDIA_PATH = 'rokanthemes/testimonial/images';

	/**
	 * slider colleciton factory
	 * @var [type]
	 */
	protected $_sliderCollectionFactory;

	/**
	 * store view id
	 * @var int
	 */
	protected $_storeViewId = null;

	protected $_testimoFactory;

	protected $_formFieldHtmlIdPrefix = 'page_';

	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;

	public function __construct(
		\Magento\Framework\Model\Context $context,
		\Magento\Framework\Registry $registry,
		\Rokanthemes\Testimonial\Model\ResourceModel\Testimo $resource,
		\Rokanthemes\Testimonial\Model\ResourceModel\Testimo\Collection $resourceCollection,
		\Rokanthemes\Testimonial\Model\TestimoFactory $testimoFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager
	) {
		parent::__construct(
			$context,
			$registry,
			$resource,
			$resourceCollection
		);
		$this->_testimoFactory = $testimoFactory;
		$this->_storeManager = $storeManager;

		if ($storeViewId = $this->_storeManager->getStore()->getId()) {
			$this->_storeViewId = $storeViewId;
		}
	}

	public function getFormFieldHtmlIdPrefix() {
		return $this->_formFieldHtmlIdPrefix;
	}

	public function getStoreAttributes() {
		return array(
			'name',
			'status',
			'click_url',
			'image_alt',
			'image',
		);
	}

	/**
	 * get store view id
	 * @return int [description]
	 */
	public function getStoreViewId() {
		return $this->_storeViewId;
	}

	/**
	 * set store view id
	 * @param int $storeViewId [description]
	 */
	public function setStoreViewId($storeViewId) {
		$this->_storeViewId = $storeViewId;
		return $this;
	}

	public function beforeSave() {
		return parent::beforeSave();
	}

	public function afterSave() {
		return parent::afterSave();
	}

	//info multistore
	public function load($id, $field = null) {
		parent::load($id, $field);
		if ($this->getStoreViewId()) {
			$this->getStoreViewValue();
		}
		return $this;
	}

	public function getStoreViewValue($storeViewId = null) {
		return $this;
	}

	public function getAvailableStatuses() {
		return array(self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled'));
	}
}
