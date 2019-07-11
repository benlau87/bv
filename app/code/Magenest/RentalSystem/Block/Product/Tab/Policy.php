<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Block\Product\Tab;

use Magento\Catalog\Block\Product\View\Description;
use Magento\Framework\View\Element\Template\Context;
use Magento\Cms\Model\Template\FilterProvider;

class Policy extends Description
{
    const XML_PATH_RENTAL_POLICY = 'rental_system/policy/policy';

    /**
     * @var FilterProvider
     */
    protected $_filterProvider;

    /**
     * Policy constructor.
     *
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        FilterProvider $filterProvider,
        array $data = []
    ) {
        parent::__construct($context, $registry, $data);
        $this->_filterProvider = $filterProvider;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getPolicy()
    {
        $policy = $this->_scopeConfig->getValue(self::XML_PATH_RENTAL_POLICY);
        if (!empty($policy))
            return $this->_filterProvider->getPageFilter()->filter($policy);
        else return '';
    }
}