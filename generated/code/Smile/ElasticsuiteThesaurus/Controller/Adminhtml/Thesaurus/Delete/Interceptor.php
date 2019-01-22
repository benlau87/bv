<?php
namespace Smile\ElasticsuiteThesaurus\Controller\Adminhtml\Thesaurus\Delete;

/**
 * Interceptor class for @see \Smile\ElasticsuiteThesaurus\Controller\Adminhtml\Thesaurus\Delete
 */
class Interceptor extends \Smile\ElasticsuiteThesaurus\Controller\Adminhtml\Thesaurus\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $coreRegistry, \Smile\ElasticsuiteThesaurus\Api\ThesaurusRepositoryInterface $thesaurusRepository, \Smile\ElasticsuiteThesaurus\Model\ThesaurusFactory $thesaurusFactory)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $coreRegistry, $thesaurusRepository, $thesaurusFactory);
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
