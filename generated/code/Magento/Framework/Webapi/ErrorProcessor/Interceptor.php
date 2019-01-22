<?php
namespace Magento\Framework\Webapi\ErrorProcessor;

/**
 * Interceptor class for @see \Magento\Framework\Webapi\ErrorProcessor
 */
class Interceptor extends \Magento\Framework\Webapi\ErrorProcessor implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Json\Encoder $encoder, \Magento\Framework\App\State $appState, \Psr\Log\LoggerInterface $logger, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\Serialize\Serializer\Json $serializer = null)
    {
        $this->___init();
        parent::__construct($encoder, $appState, $logger, $filesystem, $serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function maskException(\Exception $exception)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'maskException');
        if (!$pluginInfo) {
            return parent::maskException($exception);
        } else {
            return $this->___callPlugins('maskException', func_get_args(), $pluginInfo);
        }
    }
}
