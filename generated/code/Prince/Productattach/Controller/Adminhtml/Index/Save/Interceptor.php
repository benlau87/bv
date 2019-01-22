<?php
namespace Prince\Productattach\Controller\Adminhtml\Index\Save;

/**
 * Interceptor class for @see \Prince\Productattach\Controller\Adminhtml\Index\Save
 */
class Interceptor extends \Prince\Productattach\Controller\Adminhtml\Index\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Prince\Productattach\Controller\Adminhtml\Index\PostDataProcessor $dataProcessor, \Prince\Productattach\Model\Productattach $attachModel, \Prince\Productattach\Helper\Data $helper)
    {
        $this->___init();
        parent::__construct($context, $dataProcessor, $attachModel, $helper);
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
