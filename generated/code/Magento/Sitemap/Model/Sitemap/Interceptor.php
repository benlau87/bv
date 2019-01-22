<?php
namespace Magento\Sitemap\Model\Sitemap;

/**
 * Interceptor class for @see \Magento\Sitemap\Model\Sitemap
 */
class Interceptor extends \Magento\Sitemap\Model\Sitemap implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Escaper $escaper, \Magento\Sitemap\Helper\Data $sitemapData, \Magento\Framework\Filesystem $filesystem, \Magento\Sitemap\Model\ResourceModel\Catalog\CategoryFactory $categoryFactory, \Magento\Sitemap\Model\ResourceModel\Catalog\ProductFactory $productFactory, \Magento\Sitemap\Model\ResourceModel\Cms\PageFactory $cmsFactory, \Magento\Framework\Stdlib\DateTime\DateTime $modelDate, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\App\RequestInterface $request, \Magento\Framework\Stdlib\DateTime $dateTime, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = array(), \Magento\Config\Model\Config\Reader\Source\Deployed\DocumentRoot $documentRoot = null)
    {
        $this->___init();
        parent::__construct($context, $registry, $escaper, $sitemapData, $filesystem, $categoryFactory, $productFactory, $cmsFactory, $modelDate, $storeManager, $request, $dateTime, $resource, $resourceCollection, $data, $documentRoot);
    }
}
