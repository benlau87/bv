<?php
namespace Rokanthemes\Brand\Model\Layer\Brand;

/**
 * Interceptor class for @see \Rokanthemes\Brand\Model\Layer\Brand
 */
class Interceptor extends \Rokanthemes\Brand\Model\Layer\Brand implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Model\Layer\ContextInterface $context, \Magento\Catalog\Model\Layer\StateFactory $layerStateFactory, \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory, \Magento\Catalog\Model\ResourceModel\Product $catalogProduct, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Registry $registry, \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $layerStateFactory, $attributeCollectionFactory, $catalogProduct, $storeManager, $registry, $categoryRepository, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function prepareProductCollection($collection)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'prepareProductCollection');
        if (!$pluginInfo) {
            return parent::prepareProductCollection($collection);
        } else {
            return $this->___callPlugins('prepareProductCollection', func_get_args(), $pluginInfo);
        }
    }
}
