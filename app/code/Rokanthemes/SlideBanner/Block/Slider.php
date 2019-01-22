<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Rokanthemes\SlideBanner\Block;

/**
 * Cms block content block
 */
class Slider extends \Magento\Framework\View\Element\Template 
{
    protected $_filterProvider;
	protected $_sliderFactory;
	protected $_bannerFactory;

	protected $_scopeConfig;

	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;
	protected $_slider;

    /**
     * @param Context $context
     * @param array $data
     */
	
   public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Rokanthemes\SlideBanner\Model\SliderFactory $sliderFactory,
		\Rokanthemes\SlideBanner\Model\SlideFactory $slideFactory,
		\Magento\Cms\Model\Template\FilterProvider $filterProvider,
		array $data = []
	) {
		parent::__construct($context, $data);
		$this->_sliderFactory = $sliderFactory;
		$this->_bannerFactory = $slideFactory;
		$this->_scopeConfig = $context->getScopeConfig();
		$this->_storeManager = $context->getStoreManager();
		$this->_filterProvider = $filterProvider;
	}

    /**
     * Prepare Content HTML
     *
     * @return string
     */
    protected function _beforeToHtml()
    {
        $sliderId = $this->getSliderId();
        if ($sliderId && !$this->getTemplate()) {
			$this->setTemplate("Rokanthemes_SlideBanner::slider.phtml");
        }
        return parent::_beforeToHtml();
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
	public function getImageElement($src)
	{
		$mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		return '<img alt="' . $this->getSlider()->getSliderTitle() . '" src="'. $mediaUrl . $src . '" />';
	}
	public function getBannerCollection()
	{
		$sliderId = $this->getSlider()->getId();
		if(!$sliderId)
			return [];
		$collection = $this->_bannerFactory->create()->getCollection();
		$collection->addFieldToFilter('slider_id', $sliderId);
		$collection->addFieldToFilter('slide_status', 1);
		return $collection;
	}
	public function getSlider()
	{
		if(is_null($this->_slider)):
			$sliderId = $this->getSliderId();
			$this->_slider = $this->_sliderFactory->create();
			$this->_slider->load($sliderId);
		endif;
		return $this->_slider;
	}
	public function getContentText($html)
	{
		$html = $this->_filterProvider->getPageFilter()->filter($html);
        return $html;
	}
}
