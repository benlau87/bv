<?php
namespace Rokanthemes\Categorytab\Controller\Adminhtml\Category\Thumbnailimage\Upload;

/**
 * Interceptor class for @see \Rokanthemes\Categorytab\Controller\Adminhtml\Category\Thumbnailimage\Upload
 */
class Interceptor extends \Rokanthemes\Categorytab\Controller\Adminhtml\Category\Thumbnailimage\Upload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Catalog\Model\ImageUploader $imageUploader)
    {
        $this->___init();
        parent::__construct($context, $imageUploader);
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
