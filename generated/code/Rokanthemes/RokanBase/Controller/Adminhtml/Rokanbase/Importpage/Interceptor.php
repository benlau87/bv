<?php
namespace Rokanthemes\RokanBase\Controller\Adminhtml\Rokanbase\Importpage;

/**
 * Interceptor class for @see \Rokanthemes\RokanBase\Controller\Adminhtml\Rokanbase\Importpage
 */
class Interceptor extends \Rokanthemes\RokanBase\Controller\Adminhtml\Rokanbase\Importpage implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Response\Http\FileFactory $fileFactory, \Magento\PageCache\Model\Config $config, \Rokanthemes\RokanBase\Helper\Data $helperModule)
    {
        $this->___init();
        parent::__construct($context, $fileFactory, $config, $helperModule);
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
