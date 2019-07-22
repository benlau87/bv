<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Block\Product;

use Magento\Framework\View\Element\Template;
use Magenest\RentalSystem\Model\RentalFactory;
use Magenest\RentalSystem\Model\RentalPriceFactory;
use Magenest\RentalSystem\Model\RentalOptionFactory;
use Magenest\RentalSystem\Model\RentalOptionTypeFactory;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

/**
 * Class Rental
 * @package Magenest\RentalSystem\Block\Product
 */
class Rental extends Template
{
    /**
     * Google Map API key
     */
    const XML_PATH_GOOGLE_MAP_API_KEY = 'rental_system/general/google_api_key';

    /**
     * Universal maximum rent duration
     */
    const XML_PATH_MAX_DURATION = 'rental_system/rental/max_duration';

    /**
     * Maximum advance period
     */
    const XML_PATH_MAX_ADVANCE = 'rental_system/rental/max_advance_duration';

    /**
     * Days off
     */
    const XML_PATH_DAYS_OFF = 'rental_system/rental/days_off';

    /**
     * Work hours
     */
    const XML_PATH_WORK_HOURS = 'rental_system/rental/work_hours';

    /**
     * Locale settings
     */
    const XML_PATH_DAYS_LABEL   = 'rental_system/locale/daysOfWeek';
    const XML_PATH_MONTHS_LABEL = 'rental_system/month/m';
    const XML_PATH_SELECT_TEXT  = 'rental_system/locale/applyLabel';
    const XML_PATH_CLEAR_TEXT   = 'rental_system/locale/cancelLabel';
    const XML_PATH_DATE_FORMAT  = 'rental_system/locale/dateformat';
    const XML_PATH_FIRST_DAY    = 'rental_system/locale/firstDay';

    /**
     * Rental Policy display
     */
    const XML_PATH_POLICY_REQUIRED     = 'rental_system/policy/required';
    const XML_PATH_POLICY_CONFIRMATION = 'rental_system/policy/confirmation';
    const XML_PATH_POLICY_ERROR        = 'rental_system/policy/errormsg';

    /**
     * @var string
     */
    protected $_template = 'catalog/product/rental.phtml';

    /**
     * @var RentalFactory
     */
    protected $_rentalFactory;

    /**
     * @var RentalPriceFactory
     */
    protected $_rentalPriceFactory;

    /**
     * @var RentalOptionFactory
     */
    protected $_rentalOptionFactory;

    /**
     * @var RentalOptionTypeFactory
     */
    protected $_rentalOptionTypeFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_currency;

    /**
     * @var \Magenest\RentalSystem\Model\Rental
     */
    protected $_rental;

    /**
     * @var FormatInterface
     */
    protected $_localeFormat;

    /**
     * @var PriceHelper
     */
    protected $_price;

    /**
     * Rental constructor.
     *
     * @param RentalFactory $rentalFactory
     * @param RentalPriceFactory $rentalPriceFactory
     * @param RentalOptionFactory $rentalOptionFactory
     * @param RentalOptionTypeFactory $rentalOptionTypeFactory
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param FormatInterface $_localeFormat
     * @param PriceHelper $_price
     * @param array $data
     */
    public function __construct(
        RentalFactory $rentalFactory,
        RentalPriceFactory $rentalPriceFactory,
        RentalOptionFactory $rentalOptionFactory,
        RentalOptionTypeFactory $rentalOptionTypeFactory,
        \Magento\Catalog\Block\Product\Context $context,
        FormatInterface $_localeFormat,
        PriceHelper $_price,
        array $data = []
    ) {
        $this->_rentalFactory           = $rentalFactory;
        $this->_rentalPriceFactory      = $rentalPriceFactory;
        $this->_rentalOptionFactory     = $rentalOptionFactory;
        $this->_rentalOptionTypeFactory = $rentalOptionTypeFactory;
        $this->_coreRegistry            = $context->getRegistry();
        $this->_localeFormat            = $_localeFormat;
        $this->_price                   = $_price;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getCurrentProductId()
    {
        $id = $this->_coreRegistry->registry('current_product')->getId();

        return $id;
    }

    /**
     * Initiate rental model
     */
    public function getRental()
    {
        $this->_rental = $this->_rentalFactory->create()->load($this->getCurrentProductId(), 'product_id');
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrencySymbol()
    {
        return $this->_storeManager->getStore()->getBaseCurrency()->getCurrencySymbol();
    }

    /**
     * @return mixed
     */
    public function getRentalPrice()
    {
        $priceData = $this->_rentalPriceFactory->create()->loadByProductId($this->getCurrentProductId())->getData();
        $data      = [
            'id'          => $priceData['id'],
            'product_id'  => $priceData['product_id'],
            'base_price'  => $priceData['base_price'],
            'base_period' => $this->getPeriodStr($priceData['base_period']),
            'base_hour'   => (int)$this->getDuration($priceData['base_period']),
            'base_type'   => substr($priceData['base_period'], -1),
            'add_price'   => @$priceData['additional_price'],
            'add_period'  => $this->getPeriodStr(@$priceData['additional_period']),
            'add_hour'    => (int)$this->getDuration(@$priceData['additional_period']),
            'add_type'    => substr(@$priceData['additional_period'], -1),
        ];

        return $data;
    }

    /**
     * @return array
     */
    public function getRentalOptions()
    {
        $data    = [];
        $i       = 0;
        $options = $this->_rentalOptionFactory->create()->getCollection()
            ->addFilter('product_id', $this->getCurrentProductId());

        foreach ($options as $option) {
            /** @var \Magenest\RentalSystem\Model\RentalOption $option */
            $data[$i] = $option->getData();
            $i++;
        }

        return $data;
    }

    /**
     * Get encoded days off
     * @return false|string
     */
    public function getDaysOff()
    {
        $daysOff = $this->_scopeConfig->getValue(self::XML_PATH_DAYS_OFF);
        if (strlen($daysOff) > 0)
            $days = explode(',', $daysOff);
        else $days = [7];

        return json_encode($days);
    }

    /**
     * @return int
     */
    public function isWeekOff()
    {
        $daysOff = $this->_scopeConfig->getValue(self::XML_PATH_DAYS_OFF);
        if (strpos($daysOff, "1,2,3,4,5,6,0") === 0) {
            return 1;
        } else return 0;
    }

    /**
     * Work hours (range)
     * @return mixed
     */
    public function getWorkHours()
    {
        $workHours = $this->_scopeConfig->getValue(self::XML_PATH_WORK_HOURS);
        if (strlen($workHours) > 0) {
            $hours = explode(',', $workHours);
//            if ($hours[1] <= $hours[0])
//                $hours[1] = 23;
        } else $hours = [0, 23];

        return json_encode($hours);
    }

    /**
     * Get days of week labels
     * @return false|string
     */
    public function getDaysOfWeek()
    {
        $daysOfWeek = $this->_scopeConfig->getValue(self::XML_PATH_DAYS_LABEL);
        $labels     = explode(',', $daysOfWeek);

        return json_encode($labels);
    }

    /**
     * Get month labels
     * @return false|string
     */
    public function getMonthLabels()
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $label    = $this->_scopeConfig->getValue(self::XML_PATH_MONTHS_LABEL . $i);
            $months[] = $label;
        }

        return json_encode($months);
    }

    public function getSelectButton()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_SELECT_TEXT);
    }

    public function getClearButton()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_CLEAR_TEXT);
    }

    public function getDateFormat()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_DATE_FORMAT);
    }

    public function getFirstDay()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_FIRST_DAY);
    }


    /**
     * @param $optionId
     *
     * @return array
     */
    public function getOptionTypes($optionId)
    {
        $data  = [];
        $i     = 0;
        $types = $this->_rentalOptionTypeFactory->create()->getCollection()
            ->addFilter('product_id', $this->getCurrentProductId())
            ->addFilter('option_id', $optionId);
        foreach ($types as $type) {
            /** @var \Magenest\RentalSystem\Model\RentalOptionType $type */
            $data[$i] = $type->getData();
            $i++;
        }

        return $data;
    }

    /**
     * Get maximum advance period duration
     * @return mixed
     */
    public function getMaxAdvance()
    {
        $advance = (int)$this->_scopeConfig->getValue(self::XML_PATH_MAX_ADVANCE);
        if (!isset($advance))
            return 30;
        else
            return $advance;
    }

    /**
     * Get maximum rent duration (days)
     * @return mixed
     */
    public function getMaxDuration()
    {
        $rental   = $this->_rental;
        $leadTime = $rental->getData('lead_time');
        if ($leadTime == null || $leadTime < 0)
            $leadTime = 0;

        $duration = $rental->getData('max_duration');
        if ($duration == 0)
            return (int)$this->_scopeConfig->getValue(self::XML_PATH_MAX_DURATION) + (int)$leadTime;
        else
            return (int)$duration + (int)$leadTime;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getOptionPriceType($type)
    {
        if ($type == 'per_hour')
            return 'hour';
        else if ($type == 'per_day')
            return 'day';
        else return '';
    }

    /**
     * @return bool|mixed
     */
    public function isShipping()
    {
        $rental = $this->_rental;
        if ($rental->getData('type') == 0)
            if (!empty($rental->getData('lead_time')))
                return $rental->getData('lead_time');

        return 0;
    }

    /**
     * @return bool|mixed
     */
    public function isPickup()
    {
        $rental = $this->_rental;
        if ($rental->getData('type') == 1)
            if (!empty($rental->getData('pickup_address')))
                return $rental->getData('pickup_address');

        return false;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return (int)$this->_rental->getData('type');
    }

    /**
     * @return mixed
     */
    public function getGoogleAPIkey()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_GOOGLE_MAP_API_KEY);
    }

    /**
     * @param string $period
     *
     * @return string
     */
    public function getDuration($period)
    {
        $length   = strlen($period);
        $duration = (int)substr($period, 0, $length - 1);
        $type     = substr($period, -1);

        if ($type == 'w' || $type == 'W')
            return $duration * 168;
        else if ($type == 'D' || $type == 'd')
            return $duration * 24;
        else return $duration;
    }

    /**
     * @param string $period
     *
     * @return array
     */
    public function getPeriodStr($period)
    {
        $length   = strlen($period);
        $duration = (int)substr($period, 0, $length - 1);
        $type     = substr($period, -1);
        if ($duration > 1) {
            if ($type == 'w' || $type == 'W')
                $typeStr = 'weeks';
            else if ($type == 'd' || $type == 'D')
                $typeStr = 'days';
            else
                $typeStr = 'hours';
        } else {
            if ($type == 'w' || $type == 'W')
                $typeStr = 'week';
            else if ($type == 'd' || $type == 'D')
                $typeStr = 'day';
            else
                $typeStr = 'hour';
        }

        return [$duration, $typeStr];
    }

    /**]
     * @return false|string
     */
    public function getPriceFormatArray()
    {
        return json_encode($this->_localeFormat->getPriceFormat());
    }

    /**
     * @param $price
     *
     * @return float|string
     */
    public function getLocatePrice($price)
    {
        return $this->_price->currency($price, true, false);
    }

    /**
     * @return bool
     */
    public function isPolicyRequired()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_POLICY_REQUIRED);
    }

    public function getPolicyConfirmation()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_POLICY_CONFIRMATION);
    }

    public function getPolicyErrorMsg()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_POLICY_ERROR);
    }

    /**
     * @return mixed|string|string[]|null
     */
    public function getConfirmationStr()
    {
        $message = $this->getPolicyConfirmation();
        preg_match('#{{(.*?)}}#', $message, $match);
        if (isset($match[1])) {
            $link    = '<a class="action add" id="policy_read" href="#policy.tab">' . $match[1] . '</a>';
            $message = preg_replace('#{{(.*?)}}#', $link, $message);
        }

        return $message;
    }
}