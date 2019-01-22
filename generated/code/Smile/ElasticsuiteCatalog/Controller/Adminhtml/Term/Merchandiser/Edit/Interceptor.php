<?php
namespace Smile\ElasticsuiteCatalog\Controller\Adminhtml\Term\Merchandiser\Edit;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalog\Controller\Adminhtml\Term\Merchandiser\Edit
 */
class Interceptor extends \Smile\ElasticsuiteCatalog\Controller\Adminhtml\Term\Merchandiser\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Search\Model\QueryFactory $queryFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $queryFactory);
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
