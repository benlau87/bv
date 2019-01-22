<?php
namespace Sebwite\ProductDownloads\Controller\Adminhtml\Delete\Index;

/**
 * Interceptor class for @see \Sebwite\ProductDownloads\Controller\Adminhtml\Delete\Index
 */
class Interceptor extends \Sebwite\ProductDownloads\Controller\Adminhtml\Delete\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Sebwite\ProductDownloads\Model\Download $download, \Sebwite\ProductDownloads\Model\DownloadFactory $downloadFactory)
    {
        $this->___init();
        parent::__construct($context, $download, $downloadFactory);
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
