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

namespace Magetrend\PdfTemplates\Model\Pdf\Element\Items\Column;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Bundle item pdf renderer
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class DefaultRenderer extends \Magento\Framework\DataObject
{
    /**
     * @var \Magetrend\PdfTemplates\Helper\Data
     */
    public $moduleHelper;

    /**
     * @var \Magetrend\PdfTemplates\Model\Pdf\Element
     */
    public $element;

    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    public $imageBuilder;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    public $image;

    public $assetRepo;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    public $productRepository;

    /**
     * @var \Magento\Framework\Filesystem
     */
    public $filesystem;

    /**
     * DefaultRenderer constructor.
     * @param \Magetrend\PdfTemplates\Helper\Data $moduleHelper
     * @param \Magetrend\PdfTemplates\Model\Pdf\Element $element
     * @param array $data
     */
    public function __construct(
        \Magetrend\PdfTemplates\Helper\Data $moduleHelper,
        \Magetrend\PdfTemplates\Model\Pdf\Element $element,
        \Magento\Catalog\Block\Product\ImageBuilder $imageBuilder,
        \Magento\Catalog\Helper\Image $image,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Filesystem $filesystem,
        array $data = []
    ) {
        $this->moduleHelper = $moduleHelper;
        $this->element = $element;
        $this->imageBuilder = $imageBuilder;
        $this->image = $image;
        $this->assetRepo = $assetRepo;
        $this->productRepository = $productRepository;
        $this->filesystem = $filesystem;
        parent::__construct($data);
    }

    /**
     * Returns formated subtotal value
     *
     * @return string
     */
    public function getPdfData()
    {
        $columnName = $this->getColumn();
        $attributes = $this->getAttributes();
        $fontSize = $this->moduleHelper->removePx($attributes['table_row_font_size']);
        $lineHeight = $this->moduleHelper->removePx($attributes['table_row_line_height']);
        $fontCode = $attributes['table_row_font'];
        $color = $attributes['table_row_font_color'];

        $padding = $this->getRowPadding();
        $columnWidth = $this->moduleHelper->toPoint(
            $this->moduleHelper->removePx(
                $attributes['table_header_'.$columnName.'_column_width']
            ) - $padding[3] - $padding[1]
        );

        $value = $this->element->splitStringToLines(
            $this->getRowValue(),
            $columnWidth,
            $fontCode,
            $this->moduleHelper->toPoint($fontSize)
        );

        $data = [
            'padding' => $padding,
            'text' => [
                $columnName => [
                    'text' => $value,
                    'font' => $fontCode,
                    'font_size' => $fontSize,
                    'line_height' => $lineHeight,
                    'color' => $color
                ],
            ]
        ];
        $data['height'] = $this->getColumnHeight($data);
        return $data;
    }

    public function getColumnHeight($data)
    {
        if (empty($data['text'])) {
            return 0;
        }

        $padding = $this->getRowPadding();
        $rowHeight = $padding[0] + $padding[2];

        foreach ($data['text'] as $text) {
            $rowHeight = $rowHeight + (count($text['text'])*$text['line_height']);
        }
        return $rowHeight;
    }

    /**
     * Returns row cell padding options
     *
     * @return array
     */
    public function getRowPadding()
    {
        $attributes = $this->getAttributes();
        return [
            $this->moduleHelper->removePx($attributes['table_row_cell_padding_top']),
            $this->moduleHelper->removePx($attributes['table_row_cell_padding_right']),
            $this->moduleHelper->removePx($attributes['table_row_cell_padding_bottom']),
            $this->moduleHelper->removePx($attributes['table_row_cell_padding_left'])
        ];
    }

    public function getRowValue()
    {
        return $this->getItem()->getData($this->getColumn());
    }

    /**
     * Returns item options
     *
     * @param $item
     * @return string
     */
    public function getItemOptions()
    {
        return $this->getItemRenderer()->getFormatedItemOptions();
    }

    public function getItemImage()
    {
        $item = $this->getItem();
        $product = $this->productRepository->getById($item->getProductId(), false, 0);

        if (!$product->getId() || $product->getThumbnail() == '') {
            $placeHolderPath = $this->assetRepo->createAsset($this->image->getPlaceholder('small_image'))
                ->getPath();
            return $this->filesystem->getDirectoryRead(DirectoryList::STATIC_VIEW)->getAbsolutePath($placeHolderPath);
        }

        $image = $product->getThumbnail();
        $productImage = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath('catalog/product'.$image);

        return $productImage;
    }
}
