<?php
namespace Amazon\Core\Controller\Adminhtml\Simplepath\Poll;

/**
 * Interceptor class for @see \Amazon\Core\Controller\Adminhtml\Simplepath\Poll
 */
class Interceptor extends \Amazon\Core\Controller\Adminhtml\Simplepath\Poll implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Amazon\Core\Model\Config\SimplePath $simplePath, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory)
    {
        $this->___init();
        parent::__construct($context, $simplePath, $scopeConfig, $jsonResultFactory);
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
