<?php
namespace Rokanthemes\OpCheckout\Controller\Index\SaveCustomCheckoutData;

/**
 * Interceptor class for @see \Rokanthemes\OpCheckout\Controller\Index\SaveCustomCheckoutData
 */
class Interceptor extends \Rokanthemes\OpCheckout\Controller\Index\SaveCustomCheckoutData implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Framework\DataObjectFactory $dataObjectFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalRepositoryInterface, \Magento\Checkout\Model\Sidebar $sidebar, \Rokanthemes\OpCheckout\Helper\Data $oscHelper)
    {
        $this->___init();
        parent::__construct($context, $jsonHelper, $dataObjectFactory, $resultJsonFactory, $cartTotalRepositoryInterface, $sidebar, $oscHelper);
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
