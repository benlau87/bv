<?php
/**
 * MB "Vienas bitas" (Magetrend.com)
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */

namespace Magetrend\PdfTemplates\Model\Pdf\Element\Items\Invoice;

/**
 * Bundle item pdf renderer
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class Bundle extends \Magetrend\PdfTemplates\Model\Pdf\Element\Items\Invoice\DefaultRenderer
{
    /**
     * Returns formated subtotal value
     *
     * @return string
     */
    public function getFormatedSubtotal()
    {
        $priceForDisplay = $this->getItemPricesForDisplay();
        $rowTotal = $priceForDisplay[0]['price'] * $this->getItem()->getQty();
        return $this->getOrder()->formatPriceTxt($rowTotal);
    }

    public function getBundleItemOptions()
    {
        $bundleOptions = [];
        $item = $this->getItem();
        $order = $this->getOrder();
        if ($item instanceof \Magento\Sales\Model\Order\Item) {
            $options = $item->getProductOptions();
        } else {
            $options = $item->getOrderItem()->getProductOptions();
        }

        if ($options && isset($options['bundle_options'])) {
            foreach ($options['bundle_options'] as $option) {
                foreach ($option['value'] as $subOption) {
                    $bundleOptions[] = [
                        'label' => $subOption['title'],
                        'value' => $subOption['qty'].' x '.$order->formatPriceTxt($subOption['price'])
                    ];
                }
            }
        }

        return $bundleOptions;
    }

    public function getFormatedItemOptions()
    {
        $optionsString = parent::getFormatedItemOptions();
        if (!empty($optionsString)) {
            $optionsString = '{br}';
        }
        $options = $this->getBundleItemOptions();
        foreach ($options as $option) {
            $optionsString.= $option['label'].': '.$option['value'].'{br}';
        }

        return rtrim($optionsString, '{br}');
    }
}
