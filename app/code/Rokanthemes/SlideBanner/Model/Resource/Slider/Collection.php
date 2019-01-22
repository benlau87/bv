<?php
 
namespace Rokanthemes\SlideBanner\Model\Resource\Slider;

use Magento\Framework\DB\Select;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
     * @var string
     */
    protected $_idFieldName = 'slider_id';
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Rokanthemes\SlideBanner\Model\Slider','Rokanthemes\SlideBanner\Model\Resource\Slider'
        );
    }
}