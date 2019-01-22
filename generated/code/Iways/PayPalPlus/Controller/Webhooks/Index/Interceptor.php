<?php
namespace Iways\PayPalPlus\Controller\Webhooks\Index;

/**
 * Interceptor class for @see \Iways\PayPalPlus\Controller\Webhooks\Index
 */
class Interceptor extends \Iways\PayPalPlus\Controller\Webhooks\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Iways\PayPalPlus\Model\Webhook\EventFactory $webhookEventFactory, \Iways\PayPalPlus\Model\ApiFactory $apiFactory, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $webhookEventFactory, $apiFactory, $logger);
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
