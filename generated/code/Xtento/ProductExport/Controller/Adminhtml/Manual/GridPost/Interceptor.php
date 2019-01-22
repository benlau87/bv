<?php
namespace Xtento\ProductExport\Controller\Adminhtml\Manual\GridPost;

/**
 * Interceptor class for @see \Xtento\ProductExport\Controller\Adminhtml\Manual\GridPost
 */
class Interceptor extends \Xtento\ProductExport\Controller\Adminhtml\Manual\GridPost implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory, \Xtento\ProductExport\Helper\Module $moduleHelper, \Xtento\XtCore\Helper\Cron $cronHelper, \Xtento\ProductExport\Model\ResourceModel\Profile\CollectionFactory $profileCollectionFactory, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Xtento\ProductExport\Model\ProfileFactory $profileFactory, \Xtento\ProductExport\Helper\Entity $entityHelper, \Magento\Framework\Registry $registry, \Xtento\ProductExport\Model\ExportFactory $exportFactory, \Xtento\XtCore\Helper\Utils $utilsHelper)
    {
        $this->___init();
        parent::__construct($context, $filter, $collectionFactory, $moduleHelper, $cronHelper, $profileCollectionFactory, $scopeConfig, $profileFactory, $entityHelper, $registry, $exportFactory, $utilsHelper);
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
