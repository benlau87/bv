<?php
namespace Prince\Productattach\Controller\Adminhtml\Index\DeleteFile;

/**
 * Interceptor class for @see \Prince\Productattach\Controller\Adminhtml\Index\DeleteFile
 */
class Interceptor extends \Prince\Productattach\Controller\Adminhtml\Index\DeleteFile implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Prince\Productattach\Model\Productattach $attachModel, \Magento\Framework\Filesystem\Driver\File $file, \Magento\Framework\Filesystem $fileSystem)
    {
        $this->___init();
        parent::__construct($context, $attachModel, $file, $fileSystem);
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
