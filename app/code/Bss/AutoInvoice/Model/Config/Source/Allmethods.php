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
namespace Bss\AutoInvoice\Model\Config\Source;

use \Bss\AutoInvoice\Helper\Payment\Data;

class Allmethods implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Payment data
     *
     * @var \Magento\Payment\Helper\Data
     */
    protected $paymentData;

    /**
     * All methods constructor.
     *
     * @param \Bss\AutoInvoice\Helper\Payment\Data $paymentData
     */
    public function __construct(Data $paymentData)
    {
        $this->paymentData = $paymentData;
    }

    /**
     * To Option Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->paymentData->getPaymentMethodList(true, true, true);
    }
}
