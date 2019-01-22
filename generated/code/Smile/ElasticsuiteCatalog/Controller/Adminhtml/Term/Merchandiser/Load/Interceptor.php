<?php
namespace Smile\ElasticsuiteCatalog\Controller\Adminhtml\Term\Merchandiser\Load;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalog\Controller\Adminhtml\Term\Merchandiser\Load
 */
class Interceptor extends \Smile\ElasticsuiteCatalog\Controller\Adminhtml\Term\Merchandiser\Load implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Search\Model\QueryFactory $queryFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Smile\ElasticsuiteCatalog\Model\Search\PreviewFactory $previewFactory)
    {
        $this->___init();
        parent::__construct($context, $queryFactory, $jsonHelper, $previewFactory);
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
