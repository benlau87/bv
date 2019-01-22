<?php
namespace Mageside\MultipleCustomForms\Controller\Adminhtml\Form\Edit;

/**
 * Interceptor class for @see \Mageside\MultipleCustomForms\Controller\Adminhtml\Form\Edit
 */
class Interceptor extends \Mageside\MultipleCustomForms\Controller\Adminhtml\Form\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Mageside\MultipleCustomForms\Model\CustomFormFactory $customFormFactory, \Mageside\MultipleCustomForms\Model\ResourceModel\CustomFormFactory $customFormResourceFactory)
    {
        $this->___init();
        parent::__construct($context, $customFormFactory, $customFormResourceFactory);
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
