<?php
namespace Rokanthemes\Categorytab\Block\CateWidget;

/**
 * Interceptor class for @see \Rokanthemes\Categorytab\Block\CateWidget
 */
class Interceptor extends \Rokanthemes\Categorytab\Block\CateWidget implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, \Magento\Framework\App\Http\Context $httpContext, \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder, \Magento\CatalogWidget\Model\Rule $rule, \Magento\Widget\Helper\Conditions $conditionsHelper, \Magento\Catalog\Model\CategoryFactory $categoryFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $productCollectionFactory, $catalogProductVisibility, $httpContext, $sqlBuilder, $rule, $conditionsHelper, $categoryFactory, $data);
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
