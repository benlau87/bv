<?php
namespace Smile\ElasticsuiteCatalogOptimizer\Controller\Adminhtml\Optimizer\Preview;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalogOptimizer\Controller\Adminhtml\Optimizer\Preview
 */
class Interceptor extends \Smile\ElasticsuiteCatalogOptimizer\Controller\Adminhtml\Optimizer\Preview implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Smile\ElasticsuiteCatalogOptimizer\Model\Optimizer\PreviewFactory $previewModelFactory, \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository, \Smile\ElasticsuiteCatalogOptimizer\Api\Data\OptimizerInterfaceFactory $optimizerFactory, \Smile\ElasticsuiteCore\Search\Request\ContainerConfigurationFactory $containerConfigFactory, \Magento\Framework\Json\Helper\Data $jsonHelper)
    {
        $this->___init();
        parent::__construct($context, $previewModelFactory, $categoryRepository, $optimizerFactory, $containerConfigFactory, $jsonHelper);
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
