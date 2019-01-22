<?php

namespace Rokanthemes\Categorytab\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_objectManager;
    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        $this->_objectManager= $objectManager;
        parent::__construct($context);
    }
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getModel($model) {
        return $this->_objectManager->create($model);
    }
    public function getCurrentStore() {
        return $this->_storeManager->getStore();
    }
	public function getThumbnailImageUrl($category)
    {
        $url   = false;
		$image = $category->getCatImageThumbnail();
		if ($image) {
			if (is_string($image)) {
				$url = $this->_storeManager->getStore()->getBaseUrl(
						\Magento\Framework\UrlInterface::URL_TYPE_MEDIA
				) . 'catalog/category/' . $image;
			} else {
				$url = false;
			}
		}
	 
		return $url;
    }
}
