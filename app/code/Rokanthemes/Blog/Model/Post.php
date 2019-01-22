<?php
/**
 * Copyright © 2015 RokanThemes.com. All rights reserved.

 * @author RokanThemes Team <contact@rokanthemes.com>
 */

namespace Rokanthemes\Blog\Model;

/**
 * Post model
 *
 * @method \Rokanthemes\Blog\Model\ResourceModel\Post _getResource()
 * @method \Rokanthemes\Blog\Model\ResourceModel\Post getResource()
 * @method int getStoreId()
 * @method $this setStoreId(int $value)
 * @method string getTitle()
 * @method $this setTitle(string $value)
 * @method string getMetaKeywords()
 * @method $this setMetaKeywords(string $value)
 * @method string getMetaDescription()
 * @method $this setMetaDescription(string $value)
 * @method string getIdentifier()
 * @method $this setIdentifier(string $value)
 * @method string getContent()
 * @method $this setContent(string $value)
 * @method string getContentHeading()
 * @method $this setContentHeading(string $value)
 */
class Post extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Posts's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'rokanthemes_blog_post';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'blog_post';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    /**
     * @var \Rokanthemes\Blog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Rokanthemes\Blog\Model\ResourceModel\Category\Collection
     */
    protected $_parentCategories;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param Magento\Framework\UrlInterface $url
     * @param \Rokanthemes\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\UrlInterface $url,
        \Rokanthemes\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_url = $url;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Rokanthemes\Blog\Model\ResourceModel\Post');
    }

    /**
     * Retrieve model title
     * @param  boolean $plural
     * @return string
     */
    public function getOwnTitle($plural = false)
    {
        return $plural ? 'Post' : 'Posts';
    }

    /**
     * Retrieve true if post is active
     * @return boolean [description]
     */
    public function isActive()
    {
        return ($this->getStatus() == self::STATUS_ENABLED);
    }

    /**
     * Retrieve available post statuses
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_DISABLED => __('Disabled'), self::STATUS_ENABLED => __('Enabled')];
    }

    /**
     * Check if post identifier exist for specific store
     * return post id if post exists
     *
     * @param string $identifier
     * @param int $storeId
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }

    /**
     * Retrieve post url route path
     * @return string
     */
    public function getUrl()
    {
        return 'blog/post/'.$this->getIdentifier();
    }

    /**
     * Retrieve post url
     * @return string
     */
    public function getPostUrl()
    {
        return $this->_url->getUrl($this->getUrl());
    }

    /**
     * Retrieve post parent categories
     * @return \Rokanthemes\Blog\Model\ResourceModel\Category\Collection
     */
    public function getParentCategories()
    {
        if (is_null($this->_parentCategories)) {
            $this->_parentCategories = $this->_categoryCollectionFactory->create()
                ->addFieldToFilter('category_id', array('in' => $this->getCategories()))
                ->addStoreFilter($this->getStoreId())
                ->addActiveFilter();
        }

        return $this->_parentCategories;
    }

    /**
     * Retrieve post parent categories count
     * @return int
     */
    public function getCategoriesCount()
    {
        return count($this->getParentCategories());
    }

    /**
     * Retrieve post related posts
     * @return \Rokanthemes\Blog\Model\ResourceModel\Post\Collection
     */
    public function getRelatedPosts()
    {
        return $this->getCollection()
            ->addFieldToFilter('post_id', array('in' => $this->getRelatedPostIds() ?: array(0)))
            ->addStoreFilter($this->getStoreId());
    }

    /**
     * Retrieve post related products
     * @return \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    public function getRelatedProducts()
    {
        $collection = $this->_productCollectionFactory->create()
            ->addFieldToFilter('entity_id', array('in' => $this->getRelatedProductIds() ?: array(0)));

        if ($storeIds = $this->getStoreId()) {
            $collection->addStoreFilter($storeIds[0]);
        }

        return $collection;
    }
}
