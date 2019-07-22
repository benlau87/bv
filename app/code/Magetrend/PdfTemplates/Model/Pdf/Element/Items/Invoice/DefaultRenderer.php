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
 * Default item pdf renderer
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class DefaultRenderer
{
    /**
     * @var \Magento\Sales\Model\Order\Invoice\Item|null
     */
    public $item = null;

    /**
     * @var \Magento\Sales\Model\Order|null
     */
    public $order = null;

    /**
     * @var \Magento\Tax\Helper\Data
     */
    public $taxData;

    /**
     * DefaultRenderer constructor.
     *
     * @param \Magento\Tax\Helper\Data $taxData
     */
    public function __construct(
        \Magento\Tax\Helper\Data $taxData
    )
    {
        $this->taxData = $taxData;
    }

    /**
     * Set Item
     *
     * @param \Magento\Sales\Model\Order\Invoice\Item $item
     * @return $this
     */
    public function setItem($item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * Get Item
     *
     * @return \Magento\Sales\Model\AbstractModel
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set Order
     *
     * @param \Magento\Sales\Model\Order $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get Order
     *
     * @return \Magento\Sales\Model\Order|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Returns formated item price
     *
     * @return string
     */
    public function getFormatedItemPrice()
    {
        $priceForDisplay = $this->getItemPricesForDisplay();
        return $priceForDisplay[0]['formated_price'];
    }

    /**
     * Returns formated subtotal
     *
     * @return string
     */
    public function getFormatedSubtotal()
    {
        $priceForDisplay = $this->getItemPricesForDisplay();
        return $priceForDisplay[0]['formated_subtotal'];
    }

    /**
     * Returns item prices
     *
     * @return array
     */
    public function getItemPricesForDisplay()
    {
        $item = $this->getItem();
        $order = $this->getOrder();
        if ($this->taxData->displaySalesBothPrices()) {
            $prices = [
                [
                    'label' => __('Incl. Tax') . ':',
                    'formated_price' => $order->formatPriceTxt($item->getPriceInclTax()),
                    'price' => $item->getPriceInclTax(),
                    'formated_subtotal' => $order->formatPriceTxt($item->getRowTotalInclTax()),
                    'subtotal' => $item->getRowTotalInclTax(),
                ],
            ];
        } elseif ($this->taxData->displaySalesPriceInclTax()) {
            $prices = [
                [
                    'formated_price' => $order->formatPriceTxt($item->getPriceInclTax()),
                    'price' => $item->getPriceInclTax(),
                    'formated_subtotal' => $order->formatPriceTxt($item->getRowTotalInclTax()),
                    'subtotal' => $item->getRowTotalInclTax(),
                ],
            ];
        } else {
            $prices = [
                [
                    'formated_price' => $order->formatPriceTxt($item->getPrice()),
                    'price' => $item->getPrice(),
                    'formated_subtotal' => $order->formatPriceTxt($item->getRowTotal()),
                    'subtotal' => $item->getRowTotal(),
                ],
            ];
        }
        return $prices;
    }

    public function getItemOptions()
    {
        $result = [];
        $item = $this->getItem();
        $options = $this->getOrderItem($item)->getProductOptions();
        if ($options) {
            if (isset($options['options'])) {
                $result = array_merge($result, $options['options']);
            }
            if (isset($options['additional_options'])) {
                $result = array_merge($result, $options['additional_options']);
            }
            if (isset($options['attributes_info'])) {
                $result = array_merge($result, $options['attributes_info']);
            }
        }

        return $result;
    }

    public function getFormatedItemOptions()
    {

        $result = $this->getItemOptions();
        if (empty($result)) {
            return '';
        }
        $optionsString = '';
        foreach ($result as $option) {
            $optionsString.= $option['label'].': '.$option['value'].', ';
        }

        return rtrim($optionsString, ', ');
    }

    public function getOrderItem($item)
    {
        if ($item instanceof \Magento\Sales\Model\Order\Item) {
            return $item;
        }

        return $item->getOrderItem();
    }

}