<?php
namespace Smile\ElasticsuiteVirtualCategory\Controller\Adminhtml\Category\Virtual\Preview;

/**
 * Interceptor class for @see \Smile\ElasticsuiteVirtualCategory\Controller\Adminhtml\Category\Virtual\Preview
 */
class Interceptor extends \Smile\ElasticsuiteVirtualCategory\Controller\Adminhtml\Category\Virtual\Preview implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Smile\ElasticsuiteVirtualCategory\Model\PreviewFactory $previewModelFactory, \Magento\Catalog\Model\CategoryFactory $categoryFactory, \Magento\Framework\Json\Helper\Data $jsonHelper)
    {
        $this->___init();
        parent::__construct($context, $previewModelFactory, $categoryFactory, $jsonHelper);
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
