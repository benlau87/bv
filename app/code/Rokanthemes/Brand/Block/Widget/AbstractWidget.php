<?php
/**
 * Venustheme
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Venustheme.com license that is
 * available through the world-wide-web at this URL:
 * http://www.venustheme.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Venustheme
 * @package    Rokanthemes_Brand
 * @copyright  Copyright (c) 2014 Venustheme (http://www.venustheme.com/)
 * @license    http://www.venustheme.com/LICENSE-1.0.html
 */
namespace Rokanthemes\Brand\Block\Widget;

class AbstractWidget extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	/**
	 * @var \Rokanthemes\Brand\Helper\Data
	 */
	protected $_brandHelper;

	/**
     * @param \Magento\Framework\View\Element\Template\Context $context     
     * @param \Rokanthemes\Brand\Helper\Data                           $brandHelper 
     * @param array                                            $data        
     */
	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Rokanthemes\Brand\Helper\Data $brandHelper,
        array $data = []
        ) {
        $this->_brandHelper = $brandHelper;
        parent::__construct($context, $data);
    }

    public function getConfig($key, $default = '')
    {
        if($this->hasData($key))
        {
            return $this->getData($key);
        }
        return $default;
    }
}