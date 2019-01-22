<?php
namespace Smile\ElasticsuiteCatalog\Controller\Navigation\Filter\Ajax;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalog\Controller\Navigation\Filter\Ajax
 */
class Interceptor extends \Smile\ElasticsuiteCatalog\Controller\Navigation\Filter\Ajax implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory, \Magento\Catalog\Model\Layer\Resolver $layerResolver, \Magento\Catalog\Api\Data\CategoryInterfaceFactory $categoryFactory, $filterListPool = array())
    {
        $this->___init();
        parent::__construct($context, $jsonResultFactory, $layerResolver, $categoryFactory, $filterListPool);
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
