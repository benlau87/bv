<?php
namespace Benlau\CategoryThumbnail\Controller\Adminhtml\Category\Save;

class Plugin
{           
    //add thumnail field to $data for saving
    public function afterImagePreprocessing(\Magento\Catalog\Controller\Adminhtml\Category\Save $subject, $data)
    {
        if (isset($data['thumbnail']) && is_array($data['thumbnail'])) {
            if (!empty($data['thumbnail']['delete'])) {
                $data['thumbnail'] = null;
            } else {
                if (isset($data['thumbnail'][0]['name']) && isset($data['thumbnail'][0]['tmp_name'])) {
                    $data['thumbnail'] = $data['thumbnail'][0]['name'];
                } else {
                    unset($data['thumbnail']);
                }
            }
        }else{
            $data['thumbnail'] = null;
        }
        
        return $data;
    }

}
