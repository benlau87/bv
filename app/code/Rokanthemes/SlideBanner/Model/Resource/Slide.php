<?php
 
namespace Rokanthemes\SlideBanner\Model\Resource;
 
class Slide extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('rokanthemes_slide', 'slide_id');
    }
}