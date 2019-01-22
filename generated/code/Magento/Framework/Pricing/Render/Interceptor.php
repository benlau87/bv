<?php
namespace Magento\Framework\Pricing\Render;

/**
 * Interceptor class for @see \Magento\Framework\Pricing\Render
 */
class Interceptor extends \Magento\Framework\Pricing\Render implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Pricing\Render\Layout $priceLayout, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $priceLayout, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function render($priceCode, \Magento\Framework\Pricing\SaleableInterface $saleableItem, array $arguments = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'render');
        if (!$pluginInfo) {
            return parent::render($priceCode, $saleableItem, $arguments);
        } else {
            return $this->___callPlugins('render', func_get_args(), $pluginInfo);
        }
    }
}
