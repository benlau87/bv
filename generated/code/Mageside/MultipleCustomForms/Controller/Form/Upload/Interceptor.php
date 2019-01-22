<?php
namespace Mageside\MultipleCustomForms\Controller\Form\Upload;

/**
 * Interceptor class for @see \Mageside\MultipleCustomForms\Controller\Form\Upload
 */
class Interceptor extends \Mageside\MultipleCustomForms\Controller\Form\Upload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Mageside\MultipleCustomForms\Model\FileUploader $fileUploader, \Mageside\MultipleCustomForms\Model\CustomForm\FieldFactory $fieldFactory)
    {
        $this->___init();
        parent::__construct($context, $fileUploader, $fieldFactory);
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
