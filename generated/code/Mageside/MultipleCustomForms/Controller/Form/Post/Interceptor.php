<?php
namespace Mageside\MultipleCustomForms\Controller\Form\Post;

/**
 * Interceptor class for @see \Mageside\MultipleCustomForms\Controller\Form\Post
 */
class Interceptor extends \Mageside\MultipleCustomForms\Controller\Form\Post implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Mageside\MultipleCustomForms\Model\SubmissionFactory $submissionFactory, \Mageside\MultipleCustomForms\Model\CustomFormFactory $customFormFactory, \Mageside\MultipleCustomForms\Model\EmailSender $emailSender, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\View\LayoutFactory $layoutFactory)
    {
        $this->___init();
        parent::__construct($context, $formKeyValidator, $submissionFactory, $customFormFactory, $emailSender, $scopeConfig, $layoutFactory);
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
