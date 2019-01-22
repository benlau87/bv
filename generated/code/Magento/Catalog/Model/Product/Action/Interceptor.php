<?php
namespace Magento\Catalog\Model\Product\Action;

/**
 * Interceptor class for @see \Magento\Catalog\Model\Product\Action
 */
class Interceptor extends \Magento\Catalog\Model\Product\Action implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Catalog\Model\Product\WebsiteFactory $productWebsiteFactory, \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry, \Magento\Eav\Model\Config $eavConfig, \Magento\Catalog\Model\Indexer\Product\Eav\Processor $productEavIndexerProcessor, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $productWebsiteFactory, $indexerRegistry, $eavConfig, $productEavIndexerProcessor, $resource, $resourceCollection, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function updateAttributes($productIds, $attrData, $storeId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'updateAttributes');
        if (!$pluginInfo) {
            return parent::updateAttributes($productIds, $attrData, $storeId);
        } else {
            return $this->___callPlugins('updateAttributes', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function updateWebsites($productIds, $websiteIds, $type)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'updateWebsites');
        if (!$pluginInfo) {
            return parent::updateWebsites($productIds, $websiteIds, $type);
        } else {
            return $this->___callPlugins('updateWebsites', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        if (!$pluginInfo) {
            return parent::save();
        } else {
            return $this->___callPlugins('save', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'delete');
        if (!$pluginInfo) {
            return parent::delete();
        } else {
            return $this->___callPlugins('delete', func_get_args(), $pluginInfo);
        }
    }
}
