<?php
namespace Smile\ElasticsuiteCatalogOptimizer\Controller\Adminhtml\Optimizer\Save;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalogOptimizer\Controller\Adminhtml\Optimizer\Save
 */
class Interceptor extends \Smile\ElasticsuiteCatalogOptimizer\Controller\Adminhtml\Optimizer\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $coreRegistry, \Smile\ElasticsuiteCatalogOptimizer\Api\OptimizerRepositoryInterface $optimizerRepository, \Smile\ElasticsuiteCatalogOptimizer\Api\Data\OptimizerInterfaceFactory $optimizerFactory)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $coreRegistry, $optimizerRepository, $optimizerFactory);
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
