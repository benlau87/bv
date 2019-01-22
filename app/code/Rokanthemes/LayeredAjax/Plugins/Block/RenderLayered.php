<?php

namespace Rokanthemes\LayeredAjax\Plugins\Block;

class RenderLayered
{
    
	protected $_url;

	protected $_htmlPagerBlock;

	protected $_request;

   
	protected $_moduleHelper;

	public function __construct(
		\Magento\Framework\UrlInterface $url,
		\Magento\Theme\Block\Html\Pager $htmlPagerBlock,
		\Magento\Framework\App\RequestInterface $request,
		\Rokanthemes\LayeredAjax\Helper\Data $moduleHelper
	) {
		$this->_url = $url;
		$this->_htmlPagerBlock = $htmlPagerBlock;
		$this->_request = $request;
		$this->_moduleHelper = $moduleHelper;
	}

    public function aroundBuildUrl(\Magento\Swatches\Block\LayeredAjax\RenderLayered $subject, $proceed, $attributeCode, $optionId)
    {
		if(!$this->_moduleHelper->isEnabled()){
			return $proceed();
		}

		$value = array();
		if($requestValue = $this->_request->getParam($attributeCode)){
			$value = explode(',', $requestValue);
		}
		if(!in_array($optionId, $value)) {
			$value[] = $optionId;
		}

        $query = [$attributeCode => implode(',', $value)];

        return $this->_url->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true, '_query' => $query]);
    }
}
