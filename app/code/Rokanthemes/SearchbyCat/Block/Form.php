<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Store and language switcher block
 */
namespace Rokanthemes\SearchbyCat\Block;

use Magento\Directory\Helper\Data;
use Magento\Store\Model\Group;

class Form extends \Magento\Framework\View\Element\Template
{
    /**
     * @var bool
     */
    protected $_storeInUrl;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;
    protected $_categoryHelper;
    protected $_categoryFactory;

    /**
     * Constructs
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
		\Magento\Catalog\Helper\Category $categoryHelper,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
        array $data = []
    ) {
        $this->_postDataHelper = $postDataHelper;
		$this->_categoryHelper = $categoryHelper;
        $this->_categoryFactory = $categoryFactory;
        parent::__construct($context, $data);
    }
	public function getCategories()
	{
		return $this->_categoryHelper->getStoreCategories(true , false, true);
	}

    /**
     * @return int|null|string
     */
    public function getCurrentWebsiteId()
    {
        return $this->_storeManager->getStore()->getWebsiteId();
    }

    /**
     * @return int|null|string
     */
    public function getCurrentGroupId()
    {
        return $this->_storeManager->getStore()->getGroupId();
    }

    /**
     * @return int
     */
    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

}
