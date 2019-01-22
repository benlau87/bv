<?php 
namespace Rokanthemes\ProductTab\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\DataObject\IdentityInterface;

class Producttab extends \Magento\Catalog\Block\Product\AbstractProduct 
{
	/**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = 'Magento\Catalog\Block\Product\ProductList\Toolbar';

    /**
     * Product Collection
     *
     * @var AbstractCollection
     */
    protected $_productCollection;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $_postDataHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;
    protected $productCollectionFactory;
    protected $storeManager;
    protected $catalogConfig;
    protected $productVisibility;
    protected $scopeConfig;

    /**
     * @param Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
		\Magento\Catalog\Model\Product\Visibility $productVisibility,
        array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_postDataHelper = $postDataHelper;
        $this->categoryRepository = $categoryRepository;
        $this->urlHelper = $urlHelper;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $context->getStoreManager();
        $this->catalogConfig = $context->getCatalogConfig();
        $this->productVisibility = $productVisibility;
        parent::__construct(
            $context,
            $data
        );
    }
	public function getConfig($value=''){

	   $config =  $this->_scopeConfig->getValue('producttab/new_status/'.$value, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	   return $config; 
	 
	}
	protected function getCustomerGroupId()
	{
		$customerGroupId =   (int) $this->getRequest()->getParam('cid');
		if ($customerGroupId == null) {
			$customerGroupId = $this->httpContext->getValue(Context::CONTEXT_GROUP);
		}
		return $customerGroupId;
	}
		
	public function getFeaturedProduct() {
		$storeId    = $this->storeManager->getStore()->getId();
		$category = $this->categoryRepository->get($this->_storeManager->getStore()->getRootCategoryId());
		$products = $category->getProductCollection();
		$products = $this->productCollectionFactory->create()->setStoreId($storeId);
		$products->joinField(
            'position',
            'catalog_category_product',
            'position',
            'product_id=entity_id',
            'category_id=' . (int)$category->getId()
        );
		$products
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite($category->getId())
            ->setVisibility($this->productVisibility->getVisibleInCatalogIds());
		$products->addAttributeToFilter('featured', 1);
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
		$this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $products]
        );
		return $products;
	}
				
	public function getSaleProduct() {
			
		$storeId    = $this->storeManager->getStore()->getId();
		$layer =  $this->_catalogLayer;
		$category = $this->categoryRepository->get($this->_storeManager->getStore()->getRootCategoryId());
		$products = $category->getProductCollection();
		$products = $this->productCollectionFactory->create()->setStoreId($storeId);
		$products->joinField(
            'position',
            'catalog_category_product',
            'position',
            'product_id=entity_id',
            'category_id=' . (int)$category->getId()
        );
		$products
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite($category->getId())
            ->setVisibility($this->productVisibility->getVisibleInCatalogIds());
		$todayDate= date('Y-m-d', time());
		$products->addAttributeToFilter('special_to_date', array('date'=>true, 'from'=> $todayDate));
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
		$this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $products]
        );
		return $products;
	}

	public function getNewProduct() {
		$storeId    = $this->storeManager->getStore()->getId();
		$todayDate= date('Y-m-d', time());
		$category = $this->categoryRepository->get($this->_storeManager->getStore()->getRootCategoryId());
		$products = $category->getProductCollection();
		$products = $this->productCollectionFactory->create()->setStoreId($storeId);
		$products->joinField(
            'position',
            'catalog_category_product',
            'position',
            'product_id=entity_id',
            'category_id=' . (int)$category->getId()
        );
		$products
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite($category->getId())
            ->setVisibility($this->productVisibility->getVisibleInCatalogIds())
			->addAttributeToFilter('news_from_date', array('date'=>true, 'to'=> $todayDate))
			->addAttributeToSort('news_from_date','desc');	
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
		$this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $products]
        );
		return $products;
	}
	public function getRandomProduct() {
		$storeId    = $this->storeManager->getStore()->getId();
		$todayDate= date('Y-m-d', time());
		$category = $this->categoryRepository->get($this->_storeManager->getStore()->getRootCategoryId());
		$products = $category->getProductCollection();
		$products = $this->productCollectionFactory->create()->setStoreId($storeId);
		$products->joinField(
            'position',
            'catalog_category_product',
            'position',
            'product_id=entity_id',
            'category_id=' . (int)$category->getId()
        );
		$products
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite($category->getId())
            ->setVisibility($this->productVisibility->getVisibleInCatalogIds());	
		$ids = array_rand($products->getAllIds(), $this->getConfig('qty'));
		$products->addAttributeToFilter('entity_id', array('in'=>$ids));
        $products->setPageSize($this->getConfig('qty'))->setCurPage(1);
		$this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $products]
        );
		return $products;
	}
			
	public function getTabContent() {
		$productTabs = array();
        $class = 'active';
		if($this->getConfig('shownew'))
		{
            $title = $this->_scopeConfig->getValue('newproduct/newproduct_config/title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $class = '';
			$newProducts = $this->getLayout()->createBlock('Rokanthemes\Newproduct\Block\Newproduct')->getProducts();
			$productTabs[] = array('id'=>'new_product', 'name' => $title, 'productInfo' => $newProducts, 'class'=> $class);
		}
        if($this->getConfig('showfeature'))
        {
            $title = $this->_scopeConfig->getValue('featuredproduct/featuredproduct_config/title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $featureProduct = $this->getLayout()->createBlock('Rokanthemes\Featuredpro\Block\Featured')->getProducts();
            $productTabs[] = array('id'=>'feature_product','name' => 'Featured', 'productInfo' =>  $featureProduct, 'class'=> $class);
            $class = '';
        }
		if($this->getConfig('showonsale'))
		{
            $title = $this->_scopeConfig->getValue('onsaleproduct/onsaleproduct_config/title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$specialProducts = $this->getLayout()->createBlock('Rokanthemes\Onsaleproduct\Block\Onsaleproduct')->getProducts();
			$productTabs[] = array('id'=> 'sale_product','name' => 'Hot Sale', 'productInfo' =>  $specialProducts, 'class'=> $class);
            $class = '';
		}
		
		/*if($this->getConfig('showrandom'))
		{
			$randomProduct = $this->getLayout()->createBlock('Rokanthemes\ProductTab\Block\Randompr')->getProducts();
			$productTabs[] = array('id'=>'random_product','name' => 'All', 'productInfo' =>  $randomProduct, 'class'=> $class);
            $class = '';
		}*/
		
		return $productTabs;
	}
}