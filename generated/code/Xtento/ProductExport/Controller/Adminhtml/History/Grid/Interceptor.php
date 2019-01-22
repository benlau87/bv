<?php
namespace Xtento\ProductExport\Controller\Adminhtml\History\Grid;

/**
 * Interceptor class for @see \Xtento\ProductExport\Controller\Adminhtml\History\Grid
 */
class Interceptor extends \Xtento\ProductExport\Controller\Adminhtml\History\Grid implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Xtento\ProductExport\Helper\Module $moduleHelper, \Xtento\XtCore\Helper\Cron $cronHelper, \Xtento\ProductExport\Model\ResourceModel\Profile\CollectionFactory $profileCollectionFactory, \Magento\Framework\Registry $registry, \Magento\Framework\Escaper $escaper, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Xtento\ProductExport\Model\HistoryFactory $historyFactory)
    {
        $this->___init();
        parent::__construct($context, $moduleHelper, $cronHelper, $profileCollectionFactory, $registry, $escaper, $scopeConfig, $historyFactory);
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
