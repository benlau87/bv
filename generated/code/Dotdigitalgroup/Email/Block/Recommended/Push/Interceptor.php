<?php
namespace Dotdigitalgroup\Email\Block\Recommended\Push;

/**
 * Interceptor class for @see \Dotdigitalgroup\Email\Block\Recommended\Push
 */
class Interceptor extends \Dotdigitalgroup\Email\Block\Recommended\Push implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Dotdigitalgroup\Email\Model\ResourceModel\Catalog $catalog, \Dotdigitalgroup\Email\Helper\Data $helper, \Magento\Framework\Pricing\Helper\Data $priceHelper, \Dotdigitalgroup\Email\Helper\Recommended $recommended, \Magento\Catalog\Block\Product\Context $context, array $data = array())
    {
        $this->___init();
        parent::__construct($catalog, $helper, $priceHelper, $recommended, $context, $data);
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
