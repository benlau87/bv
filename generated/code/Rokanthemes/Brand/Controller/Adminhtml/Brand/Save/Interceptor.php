<?php
namespace Rokanthemes\Brand\Controller\Adminhtml\Brand\Save;

/**
 * Interceptor class for @see \Rokanthemes\Brand\Controller\Adminhtml\Brand\Save
 */
class Interceptor extends \Rokanthemes\Brand\Controller\Adminhtml\Brand\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Filesystem $filesystem, \Magento\Backend\Helper\Js $jsHelper)
    {
        $this->___init();
        parent::__construct($context, $filesystem, $jsHelper);
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
