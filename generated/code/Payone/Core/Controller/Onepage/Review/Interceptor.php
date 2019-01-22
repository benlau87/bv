<?php
namespace Payone\Core\Controller\Onepage\Review;

/**
 * Interceptor class for @see \Payone\Core\Controller\Onepage\Review
 */
class Interceptor extends \Payone\Core\Controller\Onepage\Review implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\View\Result\PageFactory $pageFactory, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository)
    {
        $this->___init();
        parent::__construct($context, $checkoutSession, $pageFactory, $quoteRepository);
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
