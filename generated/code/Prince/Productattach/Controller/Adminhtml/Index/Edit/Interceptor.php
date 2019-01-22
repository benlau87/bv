<?php
namespace Prince\Productattach\Controller\Adminhtml\Index\Edit;

/**
 * Interceptor class for @see \Prince\Productattach\Controller\Adminhtml\Index\Edit
 */
class Interceptor extends \Prince\Productattach\Controller\Adminhtml\Index\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \Prince\Productattach\Model\Productattach $attachModel)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $registry, $attachModel);
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
