<?php
namespace Algolia\AlgoliaSearch\Helper\Image;

/**
 * Interceptor class for @see \Algolia\AlgoliaSearch\Helper\Image
 */
class Interceptor extends \Algolia\AlgoliaSearch\Helper\Image implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Catalog\Model\Product\ImageFactory $productImageFactory, \Magento\Framework\View\Asset\Repository $assetRepo, \Magento\Framework\View\ConfigInterface $viewConfig, \Algolia\AlgoliaSearch\Helper\Logger $logger, $options = array())
    {
        $this->___init();
        parent::__construct($context, $productImageFactory, $assetRepo, $viewConfig, $logger, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWidth');
        if (!$pluginInfo) {
            return parent::getWidth();
        } else {
            return $this->___callPlugins('getWidth', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getHeight');
        if (!$pluginInfo) {
            return parent::getHeight();
        } else {
            return $this->___callPlugins('getHeight', func_get_args(), $pluginInfo);
        }
    }
}
