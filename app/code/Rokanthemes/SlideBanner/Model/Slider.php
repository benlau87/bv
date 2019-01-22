<?php
 
namespace Rokanthemes\SlideBanner\Model;
 
class Slider extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Rokanthemes\SlideBanner\Model\Resource\Slider');
    }
	public function getSliderSetting()
	{
		if(!$this->getData('slider_setting'))
			return $defaultSetting = array('items'=>1, 'itemsDesktop'=>'[1199,1]', 'itemsDesktopSmall' => '[980,3]', 'itemsTablet' => '[768,2]', 'itemsMobile' => '[479,1]', 'slideSpeed' => 500, 'paginationSpeed' => 500, 'rewindSpeed'=>500);
		$data = $this->getData('slider_setting');
		$data = json_decode($data, true);
		return $data;
	}
	public function getSetting()
	{
		$data = $this->getData('slider_setting');
		return $data;
	}
}