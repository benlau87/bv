<?php
namespace Smile\ElasticsuiteCatalog\Model\Layer\Filter\Item\Category;

/**
 * Interceptor class for @see \Smile\ElasticsuiteCatalog\Model\Layer\Filter\Item\Category
 */
class Interceptor extends \Smile\ElasticsuiteCatalog\Model\Layer\Filter\Item\Category implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\UrlInterface $url, \Magento\Theme\Block\Html\Pager $htmlPagerBlock, array $data = array())
    {
        $this->___init();
        parent::__construct($url, $htmlPagerBlock, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUrl');
        if (!$pluginInfo) {
            return parent::getUrl();
        } else {
            return $this->___callPlugins('getUrl', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRemoveUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRemoveUrl');
        if (!$pluginInfo) {
            return parent::getRemoveUrl();
        } else {
            return $this->___callPlugins('getRemoveUrl', func_get_args(), $pluginInfo);
        }
    }
}
