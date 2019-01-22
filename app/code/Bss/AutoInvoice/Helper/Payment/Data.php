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
namespace Bss\AutoInvoice\Helper\Payment;

use Magento\Framework\View\LayoutFactory;
use \Magento\Framework\App\Helper\Context;
use \Magento\Payment\Model\Method\Factory;
use \Magento\Store\Model\App\Emulation;
use \Magento\Payment\Model\Config;
use \Magento\Framework\App\Config\Initial;

class Data extends \Magento\Payment\Helper\Data
{

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param LayoutFactory $layoutFactory
     * @param \Magento\Payment\Model\Method\Factory $paymentMethodFactory
     * @param \Magento\Store\Model\App\Emulation $appEmulation
     * @param \Magento\Payment\Model\Config $paymentConfig
     * @param \Magento\Framework\App\Config\Initial $initialConfig
     */
    public function __construct(
        Context $context,
        LayoutFactory $layoutFactory,
        Factory $paymentMethodFactory,
        Emulation $appEmulation,
        Config $paymentConfig,
        Initial $initialConfig
    ) {
        parent::__construct(
            $context,
            $layoutFactory,
            $paymentMethodFactory,
            $appEmulation,
            $paymentConfig,
            $initialConfig
        );
    }

    /**
     * Get Methods
     *
     * @param $code
     * @param array $data
     * @param null $store
     * @return string
     */
    protected function getMethods($code, $data, $store = null)
    {
        if (isset($data['title'])) {
            return $data['title'];
        } else {
            return $this->getMethodInstance($code)->getConfigData('title', $store);
        }
    }

    /**
     * Sort
     *
     * @param $methods
     * @param $sorted
     * @return array
     */
    protected function sort($methods, $sorted)
    {
        if ($sorted) {
            asort($methods);
        }
        return $methods;
    }

    /**
     * Get Payment Method List
     *
     * @param bool $sorted
     * @param bool $asLabelValue
     * @param bool $withGroups
     * @param null $store
     * @return array
     */
    public function getPaymentMethodList($sorted = true, $asLabelValue = false, $withGroups = false, $store = null)
    {
        $methods = [];
        $groups = [];
        $groupRelations = [];

        foreach ($this->getPaymentMethods() as $code => $data) {
            $methods[$code] = $this->getMethods($code, $data, $store);
            if ($asLabelValue && $withGroups && isset($data['group'])) {
                $groupRelations[$code] = $data['group'];
            }
        }
        if ($asLabelValue && $withGroups) {
            $groups = $this->_paymentConfig->getGroups();
            foreach ($groups as $code => $title) {
                $methods[$code] = $title;
            }
        }
        $methods = $this->sort($methods, $sorted);
        if ($asLabelValue) {
            $labelValues = [];
            foreach ($methods as $code => $title) {
                $labelValues[$code] = [];
            }
            foreach ($methods as $code => $title) {
                if (isset($groups[$code])) {
                    $labelValues[$code]['label'] = $title;
                } elseif (isset($groupRelations[$code])) {
                    unset($labelValues[$code]);
                    $labelValues[$groupRelations[$code]]['value'][$code] = ['value' => $code, 'label' => $title];
                } else {
                    $labelValues[$code] = ['value' => $code, 'label' => $title];
                }
            }
            return $labelValues;
        }

        return $methods;
    }

}
