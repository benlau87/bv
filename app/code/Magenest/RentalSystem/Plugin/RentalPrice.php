<?php
/**
 * Created by PhpStorm.
 * User: ducanh
 * Date: 24/01/2019
 * Time: 16:35
 */

namespace Magenest\RentalSystem\Plugin;

use Magenest\RentalSystem\Model\RentalOptionFactory;
use Magenest\RentalSystem\Model\RentalOptionTypeFactory;
use Magenest\RentalSystem\Model\RentalPriceFactory;
use Magento\Framework\Pricing\Helper\Data as Price;

class RentalPrice
{
    /**
     * @var Price
     */
    protected $price;

    /**
     * @var RentalOptionFactory
     */
    protected $rentalOptionFactory;

    /**
     * @var RentalOptionTypeFactory
     */
    protected $rentalOptionTypeFactory;

    /**
     * @var RentalPriceFactory
     */
    protected $rentalPriceFactory;

    /**
     * RentalPrice constructor.
     *
     * @param RentalOptionFactory $rentalOptionFactory
     * @param RentalOptionTypeFactory $rentalOptionTypeFactory
     * @param RentalPriceFactory $rentalPriceFactory
     * @param Price $price
     */
    public function __construct(
        RentalOptionFactory $rentalOptionFactory,
        RentalOptionTypeFactory $rentalOptionTypeFactory,
        RentalPriceFactory $rentalPriceFactory,
        Price $price
    ) {
        $this->rentalOptionFactory     = $rentalOptionFactory;
        $this->rentalOptionTypeFactory = $rentalOptionTypeFactory;
        $this->rentalPriceFactory      = $rentalPriceFactory;
        $this->price                   = $price;
    }

    /**
     * @param \Magento\Catalog\Block\Product\AbstractProduct $subject
     * @param callable $proceed
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return string
     */
    public function aroundGetProductPrice(
        \Magento\Catalog\Block\Product\AbstractProduct $subject,
        callable $proceed,
        \Magento\Catalog\Model\Product $product
    ) {
        $result      = $proceed($product);
        $productId   = $product->getData('entity_id');
        $productType = $product->getData('type_id');

        if ($productType == "rental") {
            $rentalPrice = $this->rentalPriceFactory->create()->getCollection()->addFilter('product_id', $productId)->getLastItem();

            if (!empty($rentalPrice->getData())) {
                $priceData                = $rentalPrice->getData();
                $rentalBasePrice          = @$priceData['base_price'];
                $rentalBasePeriod         = @$priceData['base_period'];
                $rentalAdditionalPrice    = @$priceData['additional_price'];
                $rentalAdditionalPeriod   = @$priceData['additional_period'];
                $formattedBasePrice       = $this->price->currency($rentalBasePrice, true, false);
                $formattedAdditionalPrice = $this->price->currency($rentalAdditionalPrice, true, false);

                $basePeriod       = $this->getPeriodStr($rentalBasePeriod);
                $additionalPeriod = $this->getPeriodStr($rentalAdditionalPeriod);

                if ($rentalAdditionalPrice != null && $rentalAdditionalPeriod != null) {
                    $result = '<div class="price-box">' . $basePeriod[0] . ' ' . __($basePeriod[1]) . ': ' . '<b>' . $formattedBasePrice . '</b>' . " + " . '<b>' .
                        $formattedAdditionalPrice . '</b>' . __('/extra %1 %2', $additionalPeriod[0], __($additionalPeriod[1])) . '</p>' . '</div>';
                } else {
                    $result = '<div class="price-box">' . $basePeriod[0] . ' ' . __($basePeriod[1]) . ': ' . '<b>' . $formattedBasePrice . '</b>' . '</p>' . '</div>';
                }
            }
        }

        return $result;
    }

    public function aroundGetProductPriceHtml(
        \Magento\Catalog\Block\Product\AbstractProduct $subject,
        callable $proceed,
        \Magento\Catalog\Model\Product $product, $priceType)
    {
        $result      = $proceed($product, $priceType);
        $productId   = $product->getData('entity_id');
        $productType = $product->getData('type_id');

        if ($productType == "rental") {
            $rentalPrice = $this->rentalPriceFactory->create()->getCollection()->addFilter('product_id', $productId)->getLastItem();

            if (!empty($rentalPrice->getData())) {
                $priceData                = $rentalPrice->getData();
                $rentalBasePrice          = @$priceData['base_price'];
                $rentalBasePeriod         = @$priceData['base_period'];
                $rentalAdditionalPrice    = @$priceData['additional_price'];
                $rentalAdditionalPeriod   = @$priceData['additional_period'];
                $formattedBasePrice       = $this->price->currency($rentalBasePrice, true, false);
                $formattedAdditionalPrice = $this->price->currency($rentalAdditionalPrice, true, false);

                $basePeriod       = $this->getPeriodStr($rentalBasePeriod);
                $additionalPeriod = $this->getPeriodStr($rentalAdditionalPeriod);

                if ($rentalAdditionalPrice != null && $rentalAdditionalPeriod != null) {
                    $result = '<div class="price-box">' . $basePeriod[0] . ' ' . __($basePeriod[1]) . ': ' . '<b>' . $formattedBasePrice . '</b>' . " + " . '<b>' .
                        $formattedAdditionalPrice . '</b>' . __('/extra %1 %2', $additionalPeriod[0], __($additionalPeriod[1])) . '</p>' . '</div>';
                } else {
                    $result = '<div class="price-box">' . $basePeriod[0] . ' ' . __($basePeriod[1]) . ': ' . '<b>' . $formattedBasePrice . '</b>' . '</p>' . '</div>';
                }
            }
        }

        return $result;
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
}