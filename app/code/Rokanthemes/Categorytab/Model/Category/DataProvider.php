<?php
namespace Rokanthemes\CustomMenu\Model\Category;
 
class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
	protected function addUseDefaultSettings($category, $categoryData)
	{
    	$data = parent::addUseDefaultSettings($category, $categoryData);
 
    	if (isset($data['rt_menu_icon_img'])) {
            unset($data['rt_menu_icon_img']);
 
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $helper = $objectManager->get('Rokanthemes\CustomMenu\Helper\Data');
 
            $data['rt_menu_icon_img'][0]['name'] = $category->getData('rt_menu_icon_img');
            $data['rt_menu_icon_img'][0]['url']  	= $helper->getIconimageUrl($category);
    	}
 
    	return $data;
	}
}