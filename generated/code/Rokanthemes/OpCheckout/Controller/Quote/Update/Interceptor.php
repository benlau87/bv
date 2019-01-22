<?php
namespace Rokanthemes\OpCheckout\Controller\Quote\Update;

/**
 * Interceptor class for @see \Rokanthemes\OpCheckout\Controller\Quote\Update
 */
class Interceptor extends \Rokanthemes\OpCheckout\Controller\Quote\Update implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Framework\DataObjectFactory $dataObjectFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalRepositoryInterface, \Magento\Checkout\Model\Sidebar $sidebar, \Rokanthemes\OpCheckout\Helper\Data $oscHelper, \Rokanthemes\OpCheckout\Helper\Config $configHelper, \Magento\Checkout\Model\Cart $cart)
    {
        $this->___init();
        parent::__construct($context, $jsonHelper, $dataObjectFactory, $resultJsonFactory, $cartTotalRepositoryInterface, $sidebar, $oscHelper, $configHelper, $cart);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
