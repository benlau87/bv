<?php
namespace Rokanthemes\Blog\Block\Post\View\RelatedProducts;

/**
 * Interceptor class for @see \Rokanthemes\Blog\Block\Post\View\RelatedProducts
 */
class Interceptor extends \Rokanthemes\Blog\Block\Post\View\RelatedProducts implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, \Magento\Framework\Module\Manager $moduleManager, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $catalogProductVisibility, $moduleManager, $productCollectionFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        if (!$pluginInfo) {
            return parent::getImage($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('getImage', func_get_args(), $pluginInfo);
        }
    }
}
