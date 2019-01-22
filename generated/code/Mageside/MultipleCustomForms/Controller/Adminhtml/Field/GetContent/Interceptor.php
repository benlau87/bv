<?php
namespace Mageside\MultipleCustomForms\Controller\Adminhtml\Field\GetContent;

/**
 * Interceptor class for @see \Mageside\MultipleCustomForms\Controller\Adminhtml\Field\GetContent
 */
class Interceptor extends \Mageside\MultipleCustomForms\Controller\Adminhtml\Field\GetContent implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Mageside\MultipleCustomForms\Model\FileUploader $fileUploader)
    {
        $this->___init();
        parent::__construct($context, $productBuilder, $resultPageFactory, $fileUploader);
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
