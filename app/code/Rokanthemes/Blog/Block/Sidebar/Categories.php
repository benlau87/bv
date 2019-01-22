<?php
/**
 * Copyright © 2015 RokanThemes.com. All rights reserved.

 * @author RokanThemes Team <contact@rokanthemes.com>
 */

namespace Rokanthemes\Blog\Block\Sidebar;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog sidebar categories block
 */
class Categories extends \Magento\Framework\View\Element\Template
{
    use Widget;

    /**
     * @var string
     */
    protected $_widgetKey = 'categories';

    /**
     * @var \Rokanthemes\Blog\Model\ResourceModel\Category\Collection
     */
    protected $_categoryCollection;
    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Rokanthemes\Blog\Model\ResourceModel\Category\Collection $categoryCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Rokanthemes\Blog\Model\ResourceModel\Category\Collection $categoryCollection,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_categoryCollection = $categoryCollection;
    }

    /**
     * Get grouped categories
     * @return \Rokanthemes\Blog\Model\ResourceModel\Category\Collection
     */
    public function getGroupedChilds()
    {
        $k = 'grouped_childs';
        if (!$this->hasDat($k)) {
            $array = $this->_categoryCollection
                ->addActiveFilter()
                ->addStoreFilter($this->_storeManager->getStore()->getId())
                ->setOrder('position')
                ->getTreeOrderedArray();

            $this->setData($k, $array);
        }

        return $this->getData($k);
    }


    /**
     * Retrieve block identities
     * @return array
     */
    public function getIdentities()
    {
        return [\Magento\Cms\Model\Block::CACHE_TAG . '_blog_categories_widget'  ];
    }
}
