<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\RentalSystem\Observer\Config;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Message\ManagerInterface;

class WorkHours implements ObserverInterface
{
    const XML_PATH_WORK_HOURS = 'rental_system/rental/work_hours';

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Config
     */
    protected $_configResource;

    /**
     * @var ManagerInterface
     */
    protected $_message;

    /**
     * WorkHours constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $configResource
     * @param ManagerInterface $message
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Config $configResource,
        ManagerInterface $message
    ) {
        $this->_scopeConfig    = $scopeConfig;
        $this->_configResource = $configResource;
        $this->_message        = $message;
    }

    public function execute(Observer $observer)
    {
        try {
            $workHours = $this->_scopeConfig->getValue(self::XML_PATH_WORK_HOURS);
            $hours     = explode(',', $workHours);
            if ($hours[1] <= $hours[0]) {
                $hours[1]  = 23;
                $workHours = implode(',', $hours);
                $this->_message->addWarningMessage(__('Start hour must be earlier than End hour. End hour has been set to 11 p.m.'));
                $this->_configResource->saveConfig(self::XML_PATH_WORK_HOURS, $workHours);
            }
        } catch (\Exception $e) {
            $this->_message->addErrorMessage($e);
        }
    }
}