<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Rokanthemes\SlideBanner\Block\Adminhtml\Slide\Renderer;

/**
 * Adminhtml customers wishlist grid item action renderer for few action controls in one cell
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
	protected $_storeManager;
    /**
     * Renders column
     *
     * @param  \Magento\Framework\DataObject $row
     * @return string
     */
	public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }
    public function render(\Magento\Framework\DataObject $row)
    {
        $html = '';
		$mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if($image = $row->getSlideImage())
			$html = '<img height="50" src="' . $mediaUrl . $image . '" />';
        return $html;
    }
}
