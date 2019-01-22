<?php
namespace Smile\ElasticsuiteCore\Controller\Adminhtml\Search\Request\RelevanceConfig\Edit;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCore\Controller\Adminhtml\Search\Request\RelevanceConfig\Edit
 */
class Interceptor extends \Smile\ElasticsuiteCore\Controller\Adminhtml\Search\Request\RelevanceConfig\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Config\Model\Config\Structure $configStructure, \Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker $sectionChecker, \Smile\ElasticsuiteCore\Model\Search\Request\RelevanceConfig $backendConfig, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->___init();
        parent::__construct($context, $configStructure, $sectionChecker, $backendConfig, $resultPageFactory);
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
