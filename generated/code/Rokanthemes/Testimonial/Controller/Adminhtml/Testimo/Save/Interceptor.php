<?php
namespace Rokanthemes\Testimonial\Controller\Adminhtml\Testimo\Save;

/**
 * Interceptor class for @see \Rokanthemes\Testimonial\Controller\Adminhtml\Testimo\Save
 */
class Interceptor extends \Rokanthemes\Testimonial\Controller\Adminhtml\Testimo\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $timezone);
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
