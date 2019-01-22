<?php
namespace Dotdigitalgroup\Email\Block\Recommended\Wishlistproducts;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Block\Recommended\Wishlistproducts
 */
class Interceptor extends \Dotdigitalgroup\Email\Block\Recommended\Wishlistproducts implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Customer\Model\ResourceModel\Customer $customerResource, \Dotdigitalgroup\Email\Model\ResourceModel\Catalog $catalog, \Dotdigitalgroup\Email\Model\ResourceModel\Wishlist $wishlist, \Magento\Customer\Model\CustomerFactory $customerFactory, \Dotdigitalgroup\Email\Helper\Data $helper, \Magento\Framework\Pricing\Helper\Data $priceHelper, \Dotdigitalgroup\Email\Helper\Recommended $recommended, \Magento\Catalog\Block\Product\Context $context, array $data = array())
    {
        $this->___init();
        parent::__construct($customerResource, $catalog, $wishlist, $customerFactory, $helper, $priceHelper, $recommended, $context, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        if (!$pluginInfo) {
            return parent::getImage($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('getImage', func_get_args(), $pluginInfo);
        }
    }
}
