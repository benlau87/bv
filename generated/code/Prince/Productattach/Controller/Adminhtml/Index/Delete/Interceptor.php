<?php
namespace Prince\Productattach\Controller\Adminhtml\Index\Delete;

/**
 * Interceptor class for @see \Prince\Productattach\Controller\Adminhtml\Index\Delete
 */
class Interceptor extends \Prince\Productattach\Controller\Adminhtml\Index\Delete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Prince\Productattach\Model\Productattach $attachModel)
    {
        $this->___init();
        parent::__construct($context, $attachModel);
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
