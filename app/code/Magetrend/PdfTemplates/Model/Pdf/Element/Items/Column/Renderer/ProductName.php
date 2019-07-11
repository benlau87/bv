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

namespace Magetrend\PdfTemplates\Model\Pdf\Element\Items\Column\Renderer;

/**
 * Bundle item pdf renderer
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class ProductName extends \Magetrend\PdfTemplates\Model\Pdf\Element\Items\Column\DefaultRenderer
{
    /**
     * Returns formated subtotal value
     *
     * @return string
     */
    public function getPdfData()
    {
        $attributes = $this->getAttributes();
        $columnName = $this->getColumn();
        $item = $this->getItem();

        $showImage = false;
        if (isset($attributes['show_image']) && $attributes['show_image'] != 'false') {
            $showImage = true;
        }

        $fontSize = $this->moduleHelper->removePx($attributes['table_row_product_line_1_size']);
        $lineHeight = $this->moduleHelper->removePx($attributes['table_row_product_line_1_line_height']);
        $fontCode = $attributes['table_row_product_line_1_font'];
        $color = $attributes['table_row_product_line_1_color'];

        $fontSize2 = $this->moduleHelper->removePx($attributes['table_row_product_line_2_size']);
        $fontCode2 = $attributes['table_row_product_line_2_font'];
        $color2 = $attributes['table_row_product_line_2_color'];
        $lineHeight2 = $this->moduleHelper->removePx($attributes['table_row_product_line_2_line_height']);

        $padding = $this->getRowPadding();

        $columnWidth = $this->moduleHelper->removePx($attributes['table_header_'.$columnName.'_column_width'])
            - $padding[3] - $padding[1];
        if ($showImage) {
            $columnWidth  = $columnWidth - $attributes['image_width'] -  $attributes['image_margin_left']
                - $attributes['image_margin_right'];
        }
        $columnWidth = $this->moduleHelper->toPoint($columnWidth);

        $options = $this->getItemOptions();
        $productOptions = $this->element->splitStringToLines(
            $options,
            $columnWidth,
            $fontCode2,
            $this->moduleHelper->toPoint($fontSize2)
        );

        $hmv = $this->getItemHMV();
        $productHMV = $this->element->splitStringToLines(
            $hmv,
            $columnWidth,
            $fontCode2,
            $this->moduleHelper->toPoint($fontSize2)
        );

        $productName = $this->element->splitStringToLines(
            $item->getName(),
            $columnWidth,
            $fontCode,
            $this->moduleHelper->toPoint($fontSize)
        );

        $rowHeight = $padding[0] + count($productName) * $lineHeight
            + count($productOptions) * $lineHeight2 + $padding[2];

        $data =  [
            'height' => $rowHeight,
            'padding' => $padding,
            'text' => [
                'product_name' => [
                    'text' => $productName,
                    'font' => $fontCode,
                    'font_size' => $fontSize,
                    'line_height' => $lineHeight,
                    'color' => $color
                ],
                'product_option' => [
                    'text' => $productOptions,
                    'font' => $fontCode2,
                    'font_size' => $fontSize2,
                    'line_height' => $lineHeight2,
                    'color' => $color2
                ]
            ]
        ];

        if ($showImage) {
            $data['image'] = [
                'path' => $this->getItemImage(),
                'width' => $attributes['image_width'],
                'top' => $attributes['image_margin_top'],
                'right' => $attributes['image_margin_right'],
                'bottom' => $attributes['image_margin_bottom'],
                'left' => $attributes['image_margin_left'],
            ];
        }

        return $data;
    }
}
