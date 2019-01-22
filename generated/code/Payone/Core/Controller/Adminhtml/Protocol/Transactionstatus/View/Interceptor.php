<?php
namespace Payone\Core\Controller\Adminhtml\Protocol\Transactionstatus\View;

/**
 * Interceptor class for @see \Payone\Core\Controller\Adminhtml\Protocol\Transactionstatus\View
 */
class Interceptor extends \Payone\Core\Controller\Adminhtml\Protocol\Transactionstatus\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($context, $resultForwardFactory, $resultPageFactory);
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
