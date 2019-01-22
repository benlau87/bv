<?php
namespace Mageside\MultipleCustomForms\Controller\Adminhtml\Fieldset\Save;

/**
 * Interceptor class for @see \Mageside\MultipleCustomForms\Controller\Adminhtml\Fieldset\Save
 */
class Interceptor extends \Mageside\MultipleCustomForms\Controller\Adminhtml\Fieldset\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Mageside\MultipleCustomForms\Model\CustomFormFactory $customFormFactory, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Mageside\MultipleCustomForms\Model\CustomForm\FieldsetFactory $fieldsetFactory, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->___init();
        parent::__construct($context, $resultForwardFactory, $customFormFactory, $resultJsonFactory, $fieldsetFactory, $layoutFactory, $storeManager);
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
