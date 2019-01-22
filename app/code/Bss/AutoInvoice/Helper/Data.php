<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at thisURL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_AutoInvoice
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\AutoInvoice\Helper;

use \Magento\Framework\App\Config\ScopeConfigInterface;

class Data
{


    /**
     * Scope Config Interface
     *
     * @var ScopeConfigInterface $_scopeConfig
     *
     */
    protected $scopeConfig;

    /**
     * Data constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check Enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        if ($this->getConfigActive() && $this->getConfiginvoice()
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check Payment methods
     *
     * @param $payment
     * @return bool
     */
    public function checkPaymentmethod($payment)
    {
        if (in_array($payment, explode(',', $this->getPaymentMethods()))
        ) {
            return true;
        }
            return false;
    }

    /**
     * Enabled Shipment
     *
     * @return bool
     */
    public function enabledShipment()
    {
        $configShipment = $this->scopeConfig->getValue(
            'autoinvoice/settings/shipment',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($configShipment
        ) {
            return true;
        }
        return false;
    }

    /**
     * Get Payment Methods
     *
     * @return string
     */
    public function getPaymentMethods()
    {
        return $this->scopeConfig->getValue(
            'autoinvoice/settings/payment_methods',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Config active
     *
     * @return bool
     */
    public function getConfigActive()
    {
        return $this->scopeConfig->getValue(
            'autoinvoice/settings/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Config invoice
     *
     * @return bool
     */
    public function getConfiginvoice()
    {
        return $this->scopeConfig->getValue(
            'autoinvoice/settings/invoice',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
