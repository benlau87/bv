<?php
namespace Rokanthemes\CustomMenu\Model\Category\Attribute\Backend\Iconimage;

/**
 * Interceptor class for @see \Rokanthemes\CustomMenu\Model\Category\Attribute\Backend\Iconimage
 */
class Interceptor extends \Rokanthemes\CustomMenu\Model\Category\Attribute\Backend\Iconimage implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Psr\Log\LoggerInterface $logger, \Magento\Framework\Filesystem $filesystem, \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory)
    {
        $this->___init();
        parent::__construct($logger, $filesystem, $fileUploaderFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($object);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
