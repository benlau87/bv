<?php
namespace Payone\Core\Controller\Onepage\Debit;

/**
 * Interceptor class for @see \Payone\Core\Controller\Onepage\Debit
 */
class Interceptor extends \Payone\Core\Controller\Onepage\Debit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Payone\Core\Model\Api\Request\Managemandate $managemandateRequest, \Magento\Framework\View\Result\PageFactory $pageFactory, \Magento\Quote\Api\CartManagementInterface $cartManagement, \Magento\Checkout\Model\Type\Onepage $typeOnepage)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $managemandateRequest, $pageFactory, $cartManagement, $typeOnepage);
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
