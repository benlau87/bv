<?php
namespace Mageside\MultipleCustomForms\Controller\Adminhtml\Form\Submission\View;

/**
 * Interceptor class for @see \Mageside\MultipleCustomForms\Controller\Adminhtml\Form\Submission\View
 */
class Interceptor extends \Mageside\MultipleCustomForms\Controller\Adminhtml\Form\Submission\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->___init();
        parent::__construct($context, $storeManager);
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
