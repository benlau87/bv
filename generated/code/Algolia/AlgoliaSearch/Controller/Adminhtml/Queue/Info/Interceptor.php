<?php
namespace Algolia\AlgoliaSearch\Controller\Adminhtml\Queue\Info;

/**
 * Interceptor class for @see \Algolia\AlgoliaSearch\Controller\Adminhtml\Queue\Info
 */
class Interceptor extends \Algolia\AlgoliaSearch\Controller\Adminhtml\Queue\Info implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Algolia\AlgoliaSearch\Helper\ConfigHelper $configHelper, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Magento\Framework\App\ResourceConnection $resourceConnection)
    {
        $this->___init();
        parent::__construct($context, $configHelper, $resultJsonFactory, $resourceConnection);
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
