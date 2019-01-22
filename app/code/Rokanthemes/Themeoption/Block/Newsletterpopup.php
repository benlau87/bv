<?php 
namespace Rokanthemes\Themeoption\Block;
class Newsletterpopup extends  \Magento\Newsletter\Block\Subscribe
{
    public function getFormActionUrl()
    {
        return $this->getUrl('newsletter/subscriber/new', ['_secure' => true]);
    }
	
	public function getConfig($value=''){

	   $config =  $this->_scopeConfig->getValue('themeoption/newsletter/'.$value, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	   return $config; 
	 
	}
}