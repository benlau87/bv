<?php
namespace Benlau\CategoryThumbnail\Model\Category\DataProvider;

class Plugin
{           
    protected $_fileInfo;

    public function __construct(        
        \Magento\Catalog\Model\Category\FileInfo $fileInfo
    ) {
        $this->_fileInfo     = $fileInfo;
    }

    /**
     * Fix empty 'size' and 'type' values issue caused by 'convertValues' function in class Magento\Catalog\Model\Category\DataProvider
     * 
     * @return array
     */
    public function afterGetData(\Magento\Catalog\Model\Category\DataProvider $subject, $result)
    {
        $category = $subject->getCurrentCategory();
        $categoryData = $result[$category->getId()];

        if ( isset($categoryData['thumbnail']) ) {
            unset($categoryData['image'][0]['size']);
            unset($categoryData['image'][0]['type']);            

            $fileName = $category->getData('thumbnail');
            if ($this->_fileInfo->isExist($fileName)) {                
                $stat     = $this->_fileInfo->getStat($fileName);
                $mime     = $this->_fileInfo->getMimeType($fileName);                
                $categoryData['thumbnail'][0]['size'] = isset($stat) ? $stat['size'] : 0;
                $categoryData['thumbnail'][0]['type'] = $mime;
            }
        }

        if ( isset($categoryData['image']) ) {
            unset($categoryData['image'][0]['size']);
            unset($categoryData['image'][0]['type']);

            $fileName = $category->getData('image');
            if ($this->_fileInfo->isExist($fileName)) {                
                $stat     = $this->_fileInfo->getStat($fileName);
                $mime     = $this->_fileInfo->getMimeType($fileName);                
                $categoryData['image'][0]['size'] = isset($stat) ? $stat['size'] : 0;
                $categoryData['image'][0]['type'] = $mime;
            }
        }

        $result[$category->getId()] = $categoryData;
        
        return $result;
    }   
}
