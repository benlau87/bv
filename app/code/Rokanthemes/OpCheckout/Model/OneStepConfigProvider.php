<?php

namespace Rokanthemes\OpCheckout\Model;

class OneStepConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    protected $_configHelper;
	
    protected $_oscHelper;
	
    public function __construct(
        \Rokanthemes\OpCheckout\Helper\Config $configHelper,
        \Rokanthemes\OpCheckout\Helper\Data $oscHelper
    ) {
        $this->_configHelper = $configHelper;
        $this->_oscHelper = $oscHelper;
    }

    public function getConfig()
    {
        $output['checkout_description'] = $this->_configHelper->getOneStepConfig('general/checkout_description');
        $output['checkout_title'] = $this->_configHelper->getOneStepConfig('general/checkout_title');
        $output['show_login_link'] = (boolean) $this->_configHelper->getOneStepConfig('general/show_login_link');
        $output['is_login'] = (boolean) $this->_configHelper->isLogin();
        $output['login_link_title'] = $this->_configHelper->getOneStepConfig('general/login_link_title');
        $output['enable_items_image'] =(boolean) $this->_configHelper->getOneStepConfig('general/enable_items_image');
        $output['show_discount'] = (boolean) $this->_configHelper->getOneStepConfig('general/show_discount');
        $output['show_newsletter'] = (boolean) $this->_configHelper->canShowNewsletter();
        $output['terms_enable'] = (boolean) $this->_configHelper->canTermsAndConditions();
        $output['terms_and_con_title'] = $this->_configHelper->getTermsAndConTitle();
        $output['terms_and_con_terms_content'] = $this->_configHelper->getTermsAndConTermsContent();
        $output['terms_and_con_warning'] = $this->_configHelper->getTermsAndConWarning();
        $output['terms_and_con_warning_content'] = $this->_configHelper->getTermsAndConWarningContent();
        $output['newsletter_default_checked'] = (boolean) $this->_configHelper->getOneStepConfig('general/newsletter_default_checked');
        $output['show_shipping_address'] = (boolean) $this->_configHelper->getOneStepConfig('general/show_shipping_address');
        return $output;
    }
    
    
}
