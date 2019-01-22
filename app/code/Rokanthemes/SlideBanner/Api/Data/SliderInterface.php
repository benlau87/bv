<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Rokanthemes\SlideBanner\Api\Data;

/**
 * CMS page interface.
 * @api
 */
interface SliderInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const SLIDER_ID                	= 'slider_id';
    const IDENTIFIER               	= 'identifier';
    const SLIDER_TITLE             	= 'slider_title';
    const SLIDER_STATUS             = 'slider_status';
    const SLIDER_SETTING            = 'slider_setting';
    const STOREIDS         			= 'storeids';
    const SLIDER_STYLE          	= 'slider_styles';
    const SLIDER_SCRIPT             = 'slider_script';
    const CREATED_AT            	= 'created_at';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();
    public function setId($value);
	
    public function getIdentifier();
    public function setIdentifier($value);
	
    public function getSliderTitle();
    public function setSliderTitle($value);
	
    public function getSliderStatus();
    public function setSliderStatus($value);
	
    public function getSliderSetting();
    public function setSliderSetting($value);
	
    public function getStoreids();
    public function setStoreids($value);
	
    public function getSliderStyles();
    public function setSliderStyles($value);
	
    public function getSliderScript();
    public function setSliderScript($value);
	
    public function getCreatedAt();
    public function setCreatedAt($value);
}
