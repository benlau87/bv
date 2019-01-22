<?php
namespace Payone\Core\Controller\Adminhtml\Config\Export\Index;

/**
 * Interceptor class for @see \Payone\Core\Controller\Adminhtml\Config\Export\Index
 */
class Interceptor extends \Payone\Core\Controller\Adminhtml\Config\Export\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Payone\Core\Model\Config\Export $configExport, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory)
    {
        $this->___init();
        parent::__construct($context, $configExport, $resultRawFactory);
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
