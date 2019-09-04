<?php
namespace Rokanthemes\CustomMenu\Model\Category;
 
class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
	public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $category = $this->getCurrentCategory();
        if ($category) {
            $categoryData = $category->getData();
            $categoryData = $this->addUseConfigSettings($categoryData);
            $categoryData = $this->filterFields($categoryData);
            $categoryData = $this->convertValues($category, $categoryData);

            $this->loadedData[$category->getId()] = $categoryData;
        }
        return $this->loadedData;
    }
	private function convertValues($category, $categoryData)
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$media_url = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
		
		if (isset($categoryData['image'])) {
            unset($categoryData['image']);
			$categoryData['image'][0]['name'] = $category->getData('image');
			$categoryData['image'][0]['url'] = $category->getImageUrl('image');
			$categoryData['image'][0]['size'] = @filesize($directory->getPath('media').'/catalog/category/'.$category->getData('image'));
    	}
		
		if (isset($categoryData['rt_menu_icon_img'])) {
            unset($categoryData['rt_menu_icon_img']);
			$fileName2 = $category->getData('rt_menu_icon_img');
			$helper = $objectManager->get('Rokanthemes\CustomMenu\Helper\Data');
			$categoryData['rt_menu_icon_img'][0]['name'] = $category->getData('rt_menu_icon_img');
			$categoryData['rt_menu_icon_img'][0]['url'] = $helper->getIconimageUrl($category);
			$categoryData['rt_menu_icon_img'][0]['size'] = @filesize($directory->getPath('media').'/catalog/category/'.$fileName2);
    	}
		if (isset($categoryData['vc_menu_icon_img'])) {
            unset($categoryData['vc_menu_icon_img']);
			$fileName2 = $category->getData('vc_menu_icon_img');
			$helper = $objectManager->get('Rokanthemes\VerticalMenu\Helper\Data');
			$categoryData['vc_menu_icon_img'][0]['name'] = $category->getData('vc_menu_icon_img');
			$categoryData['vc_menu_icon_img'][0]['url'] = $helper->getVerticalIconimageUrl($category);
			$categoryData['vc_menu_icon_img'][0]['size'] = @filesize($directory->getPath('media').'/catalog/category/'.$fileName2);
    	}
		if (isset($categoryData['cat_image_thumbnail'])) {
            unset($categoryData['cat_image_thumbnail']);
			$fileName3 = $category->getData('cat_image_thumbnail');			
			$helper = $objectManager->get('Rokanthemes\Categorytab\Helper\Data');
			$categoryData['cat_image_thumbnail'][0]['name'] = $category->getData('cat_image_thumbnail');
			$categoryData['cat_image_thumbnail'][0]['url'] = $helper->getThumbnailImageUrl($category);
			$categoryData['cat_image_thumbnail'][0]['size'] = @filesize($directory->getPath('media').'/catalog/category/'.$fileName3);
    	}
		
        return $categoryData;
    }
}