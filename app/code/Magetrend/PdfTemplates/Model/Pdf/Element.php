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

namespace Magetrend\PdfTemplates\Model\Pdf;

/**
 * Draw pdf element class
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class Element
{
    const ALIGN_LEFT = 'left';

    const ALIGN_RIGHT = 'right';

    public $configClassName = '';

    /**
     * @var \Magento\Sales\Model\AbstractModel
     */
    public $source;

    /**
     * @var \Magetrend\PdfTemplates\Model\Config\Source\Font
     */
    public $fontConfig;

    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    public $invoice;

    /**
     * @var \Magetrend\PdfTemplates\Model\Template
     */
    public $template;


    public $orderFilter;

    /**
     * @var \Magetrend\PdfTemplates\Model\Pdf\Filter\Invoice
     */
    public $invoiceFilter;

    /**
     * @var Filter\Creditmemo
     */
    public $creditmemoFilter;

    /**
     * @var Filter\Shipment
     */
    public $shipmentFilter;

    /**
     * @var \Zend_Pdf_Page
     */
    public $pdfPage;

    /**
     * @var array
     */
    public $elementData;

    /**
     * @var int
     */
    public $ppi;

    /**
     * @var \Zend_Pdf_Page
     */
    public $currentPage;

    /**
     * @var int
     */
    public $totalPages;

    /**
     * @var int
     */
    public $pageHeight;

    /**
     * @var int
     */
    public $pageWidth;

    /**
     * @var array
     */
    public $fonts = [];

    /**
     * @var \Magento\Framework\Filesystem
     */
    public $fileSystem;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadFactory
     */
    public $readFactory;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    public $repository;

    /**
     * @var
     */
    public $order = null;

    /**
     * @var
     */
    public $elements;

    /**
     * @var \Magento\Sales\Model\Order\Pdf\Config
     */
    public $pdfConfig;

    /**
     * @var \Magento\Sales\Model\Order\Pdf\Total\Factory
     */
    public $pdfTotalFactory;

    /**
     * @var bool
     */
    public $bottomY = false;

    /**
     * @var int
     */
    public $width = 0;

    /**
     * @var int
     */
    public $height = 0;

    /**
     * @var int
     */
    public $top = 0;

    /**
     * @var int
     */
    public $left = 0;

    /**
     * Tax data
     *
     * @var \Magento\Tax\Helper\Data
     */
    public $taxData;

    /**
     * @var Element\Items\Invoice\Bundle
     */
    public $bundleItemRenderer;

    /**
     * @var Element\Items\Invoice\DefaultRenderer
     */
    public $defaultItemRenderer;

    /**
     * @var \Magento\Sales\Model\Order\Pdf\Invoice
     */
    public $magentoPdfTools;

    /**
     * @var \Magetrend\PdfTemplates\Helper\QRcode
     */
    public $qrCodeHelper;

    public $objectManager;

    public $moduleHelper;

    public $attributes = null;

    public $registry;

    /**
     * Element constructor.
     *
     * @param \Magetrend\PdfTemplates\Model\Config\Source\Font $fontConfig
     * @param \Magento\Framework\Filesystem $fileSystem
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     * @param \Magento\Framework\View\Asset\Repository $repository
     * @param \Magento\Sales\Model\Order\Pdf\Config $pdfConfig
     * @param \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory
     * @param Filter\Invoice $invoiceFilter
     * @param \Magento\Tax\Helper\Data $taxData
     * @param Element\Items\Invoice\DefaultRenderer $defaultItemRenderer
     * @param Element\Items\Invoice\Bundle $bundleItemRenderer
     * @param \Magento\Sales\Model\Order\Pdf\Invoice $magentoPdfTools
     * @param \Magetrend\PdfTemplates\Helper\QRcode $qrCodeHelper
     */
    public function __construct(
        \Magetrend\PdfTemplates\Model\Config\Source\Font $fontConfig,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Magento\Framework\View\Asset\Repository $repository,
        \Magento\Sales\Model\Order\Pdf\Config $pdfConfig,
        \Magento\Sales\Model\Order\Pdf\Total\Factory $pdfTotalFactory,
        \Magetrend\PdfTemplates\Model\Pdf\Filter\Order $orderFilter,
        \Magetrend\PdfTemplates\Model\Pdf\Filter\Invoice $invoiceFilter,
        \Magetrend\PdfTemplates\Model\Pdf\Filter\Creditmemo $creditmemoFilter,
        \Magetrend\PdfTemplates\Model\Pdf\Filter\Shipment $shipmentFilter,
        \Magento\Tax\Helper\Data $taxData,
        \Magetrend\PdfTemplates\Model\Pdf\Element\Items\Invoice\DefaultRenderer $defaultItemRenderer,
        \Magetrend\PdfTemplates\Model\Pdf\Element\Items\Invoice\Bundle $bundleItemRenderer,
        \Magento\Sales\Model\Order\Pdf\Invoice $magentoPdfTools,
        \Magetrend\PdfTemplates\Helper\QRcode $qrCodeHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magetrend\PdfTemplates\Helper\Data $moduleHelper,
        \Magento\Framework\Registry $registry
    ) {
        $this->fontConfig = $fontConfig;
        $this->fileSystem = $fileSystem;
        $this->readFactory = $readFactory;
        $this->repository = $repository;
        $this->pdfConfig = $pdfConfig;
        $this->pdfTotalFactory = $pdfTotalFactory;
        $this->orderFilter = $orderFilter;
        $this->invoiceFilter = $invoiceFilter;
        $this->creditmemoFilter = $creditmemoFilter;
        $this->shipmentFilter = $shipmentFilter;
        $this->taxData = $taxData;
        $this->defaultItemRenderer = $defaultItemRenderer;
        $this->bundleItemRenderer = $bundleItemRenderer;
        $this->magentoPdfTools = $magentoPdfTools;
        $this->qrCodeHelper = $qrCodeHelper;
        $this->objectManager = $objectManager;
        $this->moduleHelper = $moduleHelper;
        $this->registry = $registry;
    }

    /**
     * Draw Element
     *
     * @param $pdfPage
     * @param $elemetData
     * @param $source
     * @param $template
     * @param $elements
     * @param $currentPage
     */
    public function draw($pdfPage, $elemetData, $source, $template, $elements, $currentPage)
    {
        $this->invoice = $source;
        $this->source = $source;
        $this->template = $template;
        $this->pdfPage = $pdfPage;
        $this->elementData = $elemetData;
        $this->elements = $elements;
        $this->attributes = null;
        $this->ppi = $template->getPpi();
        $this->pageHeight = $this->pdfPage->getHeight();
        $this->pageWidth = $this->pdfPage->getWidth();
        $this->currentPage = $currentPage;
        $this->order = $source->getOrder();
    }

    /**
     * Returns element attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        if ($this->attributes == null) {
            $this->attributes = $this->moduleHelper->removePx($this->elementData['attributes'], $this->getFieldListToRemovePx());
        }

        return $this->attributes;
    }

    /**
     * Resets page text format
     */
    public function resetLines()
    {
        $this->pdfPage->setLineWidth(0);
        $this->pdfPage->setFillColor($this->getPdfColor('#ffffff'));
        $this->pdfPage->setLineColor($this->getPdfColor('#ffffff'));
    }

    /**
     * Convert px to points
     *
     * @param $px
     * @return float|int
     */
    public function toPoint($px)
    {
        $px = str_replace('px', '', $px);
        $px = ((int)$px) * 72 / $this->ppi;
        return $px;
    }

    /**
     * Invert Y to bottom to top cordinates
     *
     * @param $point
     * @return int
     */
    public function invertY($point)
    {
        return $this->pageHeight - $point;
    }

    /**
     * Returns pdf color by color code
     *
     * @param $color
     * @return \Zend_Pdf_Color_Html
     */
    public function getPdfColor($color)
    {
        if (substr_count($color, 'rgba') == 1) {
            list($r, $g, $b, $a) = sscanf($color, "rgba(%d, %d, %d, %f)");
            $color = sprintf("#%02x%02x%02x", $r, $g, $b);
        } elseif (substr_count($color, 'rgb') == 1) {
            list($r, $g, $b) = sscanf($color, "rgb(%d, %d, %d)");
            $color = sprintf("#%02x%02x%02x", $r, $g, $b);
        } else {
            $color = "#000000";
        }
        return new \Zend_Pdf_Color_Html($color);
    }

    /**
     * Returns pdf font by font code
     *
     * @param $fontCode
     * @return mixed
     */
    public function getPdfFont($fontCode)
    {
        if (!isset($this->fonts[$fontCode])) {
            $fontList = $this->fontConfig->getFontList();
            foreach ($fontList as $font) {
                if ($fontCode == $font['code']) {
                    $this->fonts[$fontCode] = \Zend_Pdf_Font::fontWithPath($font['path']);
                }
            }
        }

        if (isset($this->fonts[$fontCode])) {
            return $this->fonts[$fontCode];
        }

        return \Zend_Pdf_Font::fontWithPath($fontList[0]['path']);
    }

    /**
     * Removes all hidden char from string
     *
     * @param $string
     * @return mixed|string
     */
    public function cleanString($string)
    {
        $s = trim($string);
        $encoding = mb_detect_encoding($s, mb_detect_order(), false);
        if ($encoding == "UTF-8") {
            $s = mb_convert_encoding($s, 'UTF-8', 'UTF-8');
        }
        $s = iconv(mb_detect_encoding($s, mb_detect_order(), false), "UTF-8//IGNORE", $s);
        $s = preg_replace(
            '/(?>[\x00-\x1F]|\xC2[\x80-\x9F]|\xE2[\x80-\x8F]{2}|\xE2\x80[\xA4-\xA8]|\xE2\x81[\x9F-\xAF])/',
            ' ',
            $s
        );
        $s = preg_replace('/\s+/', ' ', $s);
        return $s;
    }

    /**
     * Calculate element top-left corner position
     *
     * @param $top
     * @param $left
     * @return array
     */
    public function getTopPosition($top, $left)
    {
        $y = $this->invertY($this->toPoint($top));
        $x = $this->toPoint($left);
        return ['x' => $x, 'y' => $y];
    }

    /**
     * Calculate element bottom-right corner position
     *
     * @param $top
     * @param $left
     * @return array
     */
    public function getBottomPosition($top, $left, $width, $height)
    {
        $topPosition = $this->getTopPosition($top, $left);
        $x = $topPosition['x'] + $this->toPoint($width);
        $y = $topPosition['y'] - $this->toPoint($height);
        return ['x' => $x, 'y' => $y];
    }

    /**
     * Draw element borders
     *
     * @param $top
     * @param $left
     * @param $width
     * @param $height
     * @param $size
     * @param $color
     * @param string $type
     */
    public function drawBorders($top, $left, $width, $height, $size, $color, $type = 'solid')
    {
        switch ($type) {
            case 'solid':
                $this->drawBordersSolid($top, $left, $width, $height, $size, $color);
                break;
            case 'dashed':
                $this->drawBordersDashed($top, $left, $width, $height, $size, $color);
                break;
        }
    }

    public function drawBordersDashed($top, $left, $width, $height, $size, $color)
    {
        $top = $this->removePx($top);
        $left = $this->removePx($left);
        $width = $this->removePx($width);
        $height = $this->removePx($height);
        $size = $this->removePx($size);

        if (!is_array($size)) {
            $size = [$size, $size, $size, $size];
        }

        $this->pdfPage->setLineWidth(0);
        $this->pdfPage->setFillColor($this->getPdfColor($color));
        $method = \Zend_Pdf_Page::SHAPE_DRAW_FILL;

        //top line
        $topPos = $this->getTopPosition($top, $left);
        $bottomPos = $this->getBottomPosition($top, $left, $width, $size[0]);
        if ($topPos['y'] > $bottomPos['y']) {
            $this->drawDashedLine($topPos['x'], $topPos['y'], $bottomPos['x'], $bottomPos['y'], $method);
        }
        //bottom line
        $topPos = $this->getTopPosition(($top + $height - $size[2]), $left);
        $bottomPos = $this->getBottomPosition(($top + $height - $size[2]), $left, $width, $size[2]);
        if ($topPos['y'] > $bottomPos['y']) {
            $this->drawDashedLine($topPos['x'], $topPos['y'], $bottomPos['x'], $bottomPos['y'], $method);
        }

        //left line
        $topPos = $this->getTopPosition($top, $left);
        $bottomPos = $this->getBottomPosition($top, $left, $size[3], $height);
        if ($topPos['x'] < $bottomPos['x']) {
            $this->drawDashedLine($topPos['x'], $topPos['y'], $bottomPos['x'], $bottomPos['y'], $method);
        }

        //right line
        $topPos = $this->getTopPosition($top, ($left + $width - $size[1]));
        $bottomPos = $this->getBottomPosition($top, ($left + $width - $size[1]), $size[1], $height);
        if ($topPos['x'] < $bottomPos['x']) {
            $this->drawDashedLine($topPos['x'], $topPos['y'], $bottomPos['x'], $bottomPos['y'], $method);
        }
    }

    public function drawDashedLine($x1, $y1, $x2, $y2)
    {

        if (abs($x1 - $x2) > abs($y1 - $y2)) {
            $this->drawHorizontalDashedLine($x1, $y1, $x2, $y2);
        } else {
            $this->drawVerticalDashedLine($x1, $y1, $x2, $y2);
        }
    }

    public function drawHorizontalDashedLine($x1, $y1, $x2, $y2)
    {
        $method = \Zend_Pdf_Page::SHAPE_DRAW_FILL;
        $height = abs($y1 - $y2);
        for ($i = $x1; $i <= $x2; $i = $i+($height*3)) {
            $endPoint = $i+($height*2);
            if ($endPoint > $x2) {
                $endPoint = $x2;
            }
            $this->pdfPage->drawRectangle($i, $y1, $endPoint, $y1-$height, $method);
        }
    }

    public function drawVerticalDashedLine($x1, $y1, $x2, $y2)
    {
        $method = \Zend_Pdf_Page::SHAPE_DRAW_FILL;
        $height = abs($x1 - $x2);
        for ($i = $y2; $i <= $y1; $i = $i + ($height*3)) {
            $endPoint = $i+($height*2);
            if ($endPoint > $y1) {
                $endPoint = $y1;
            }
            $this->pdfPage->drawRectangle($x1, $i, $x1+$height, $endPoint, $method);
        }
    }

    /**
     * Draw solid borders
     *
     * @param $top
     * @param $left
     * @param $width
     * @param $height
     * @param $size
     * @param $color
     */
    public function drawBordersSolid($top, $left, $width, $height, $size, $color)
    {
        $top = $this->removePx($top);
        $left = $this->removePx($left);
        $width = $this->removePx($width);
        $height = $this->removePx($height);
        $size = $this->removePx($size);

        if (!is_array($size)) {
            $size = [$size, $size, $size, $size];
        }

        $this->pdfPage->setLineWidth(0);
        $this->pdfPage->setFillColor($this->getPdfColor($color));
        $method = \Zend_Pdf_Page::SHAPE_DRAW_FILL;

        //top line
        $topPos = $this->getTopPosition($top, $left);
        $bottomPos = $this->getBottomPosition($top, $left, $width, $size[0]);
        if ($topPos['y'] > $bottomPos['y']) {
            $this->pdfPage->drawRectangle($topPos['x'], $topPos['y'], $bottomPos['x'], $bottomPos['y'], $method);
        }
        //bottom line
        $topPos = $this->getTopPosition(($top + $height - $size[2]), $left);
        $bottomPos = $this->getBottomPosition(($top + $height - $size[2]), $left, $width, $size[2]);
        if ($topPos['y'] > $bottomPos['y']) {
            $this->pdfPage->drawRectangle($topPos['x'], $topPos['y'], $bottomPos['x'], $bottomPos['y'], $method);
        }

        //left line
        $topPos = $this->getTopPosition($top, $left);
        $bottomPos = $this->getBottomPosition($top, $left, $size[3], $height);
        if ($topPos['x'] < $bottomPos['x']) {
            $this->pdfPage->drawRectangle($topPos['x'], $topPos['y'], $bottomPos['x'], $bottomPos['y'], $method);
        }

        //right line
        $topPos = $this->getTopPosition($top, ($left + $width - $size[1]));
        $bottomPos = $this->getBottomPosition($top, ($left + $width - $size[1]), $size[1], $height);
        if ($topPos['x'] < $bottomPos['x']) {
            $this->pdfPage->drawRectangle($topPos['x'], $topPos['y'], $bottomPos['x'], $bottomPos['y'], $method);
        }
    }

    /**
     * Calculate image position
     *
     * @param $top
     * @param $left
     * @param $width
     * @param $height
     * @return array
     */
    public function getImagePosition($top, $left, $width, $height)
    {
        $defaultTop = $this->getTopPosition($top, $left);
        $width = $this->toPoint($width);
        $height = $this->toPoint($height);
        $y1 = $defaultTop['y'] - $height;
        $y2 = $defaultTop['y'];

        $x1 = $defaultTop['x'];
        $x2 = $defaultTop['x'] + $width;

        return ['x1' => $x1, 'y1' => $y1, 'x2' => $x2, 'y2' => $y2];
    }

    /**
     * Remove 'px' from string or array
     *
     * @param $data
     * @param $fields
     * @return array|string
     */
    public function removePx($data, $fields = [])
    {
        if (is_array($data)) {
            if (empty($fields)) {
                foreach ($data as $key => $value) {
                    $data[$key] = (int)str_replace('px', '', $value);
                }
            } else {
                foreach ($fields as $key) {
                    $data[$key] = (int)str_replace('px', '', $data[$key]);
                }
            }
        } else {
            $data = (int)str_replace('px', '', $data);
        }
        return $data;
    }

    /**
     * Returns the total width in points of the string using the specified font and
     * size.
     *
     * @param  string $string
     * @param  \Zend_Pdf_Resource_Font $font
     * @param  float $fontSize Font size in points
     * @return float
     */
    public function widthForStringUsingFontSize($string, $font, $fontSize)
    {
        $stringWidth = $this->magentoPdfTools->widthForStringUsingFontSize($string, $font, $fontSize);
        return $stringWidth;
    }

    /**
     * Returns max character amount
     *
     * @return mixed
     */
    public function findMaxLenghtOfString($string, $maxWidth, $font, $fontSize)
    {
        $charNumber = strlen($string);
        if ($this->widthForStringUsingFontSize($string, $font, $fontSize) < $maxWidth) {
            return $charNumber;
        }

        $max = $charNumber;
        for ($i = 0; $i <= $charNumber; $i+=10) {
            $str = substr($string, 0, $i);
            $strWidth = $this->widthForStringUsingFontSize($str, $font, $fontSize);
            if ($strWidth < $maxWidth) {
                continue;
            }
            $max = $i - 10;
            break;
        }

        for ($i = $max; $i <= $charNumber; $i+=5) {
            $str = substr($string, 0, $i);
            $strWidth = $this->widthForStringUsingFontSize($str, $font, $fontSize);
            if ($strWidth < $maxWidth) {
                continue;
            }
            $max = $i - 5;
            break;
        }

        for ($i = $max; $i <= $charNumber; $i++) {
            $str = substr($string, 0, $i);
            $strWidth = $this->widthForStringUsingFontSize($str, $font, $fontSize);
            if ($strWidth < $maxWidth) {
                continue;
            }
            $max = $i - 1;
            break;
        }

        return $max;
    }

    /**
     * Split text to strings with fixed width
     *
     * @param $string
     * @param $maxWidth
     * @param $fontCode
     * @param $fontSize
     * @return array|float
     */
    public function splitStringToLines($string, $maxWidth, $fontCode, $fontSize)
    {
        if (empty($string)) {
            return [];
        }

        $string = str_replace(['<br/>', '<br>', '</br>', "\n"], '{br}', $string);
        if (substr_count($string, '{br}') > 0) {
            $lines = [];
            $substrings = explode('{br}', $string);
            foreach ($substrings as $sub) {
                $subLines = $this->splitStringToLines($sub, $maxWidth, $fontCode, $fontSize);
                foreach ($subLines as $sbLine) {
                    array_push($lines, $sbLine);
                }
            }
            return $lines;
        }

        $font = $this->getPdfFont($fontCode);
        $textWidth = $this->widthForStringUsingFontSize($string, $font, $fontSize);
        $lines = ceil($textWidth / $maxWidth);
        $maxCharInLine = $this->findMaxLenghtOfString($string, $maxWidth, $font, $fontSize);
        $string = explode(' ', $string);
        $wordList = [];

        foreach ($string as $word) {
            $wordLength = strlen($word);
            if ($wordLength > $maxCharInLine) {
                $linesCount = ceil($wordLength / $maxCharInLine);
                for ($j = 0; $j < $linesCount; $j++) {
                    $start = $j*$maxCharInLine;
                    $wordList[] = substr($word, $start, $maxCharInLine);
                }
            } else {
                $wordList[] = $word;
            }
        }

        $lines = [0 => ''];
        $i = 0;
        foreach ($wordList as $word) {
            if (strlen($lines[$i].$word) > $maxCharInLine) {
                $i++;
                $lines[$i] = '';
            }
            $lines[$i] = $lines[$i].$word.' ';
        }

        return $lines;
    }

    /**
     * Draw text lines
     *
     * @param $lines
     * @param $x1
     * @param $y1
     * @param $lineHeight
     * @param $fontSize
     * @return mixed
     */
    public function drawTextLines($lines, $x1, $y1, $lineHeight, $fontSize, $align = self::ALIGN_LEFT)
    {
        $font = $this->pdfPage->getFont();
        $lineRate = $lineHeight / $font->getLineHeight();
        $y1 = $y1 + $font->getAscent() * $lineRate;

        foreach ($lines as $line) {
            if ($align == self::ALIGN_RIGHT) {
                $x1 = $x1 - $this->widthForStringUsingFontSize($line, $font, $fontSize);
            }
            try {
                $this->pdfPage->drawText(
                    $line,
                    $x1,
                    $this->invertY($y1),
                    'UTF-8'
                );
            } catch (\Exception $e) {
                $y1 = $y1 + $lineHeight;
                continue;
            }
            $y1 = $y1 + $lineHeight;
        }

        return $y1 - $lineHeight;
    }

    /**
     * Returns element bottom line Y
     *
     * @return bool
     */
    public function getBottomY()
    {
        return $this->bottomY;
    }

    /**
     * Returns element information for next element
     *
     * @return array
     */
    public function getInfo($elementData)
    {
        $attributes = $this->getAttributes();
        $info =  [
            'design_top' => $this->removePx($elementData['attributes']['top']),
            'design_left' => $this->removePx($elementData['attributes']['left']),
            'design_width' => $this->removePx($elementData['attributes']['width']),
            'design_height' => $this->removePx($elementData['attributes']['height']),
            'pdf_top' => $this->removePx($attributes['top']),
            'pdf_left' => $this->removePx($attributes['left']),
            'pdf_width' => $this->removePx($attributes['width']),
            'pdf_height' => $this->removePx($attributes['height']),
        ];

        if (isset($attributes['depends_on']) && !empty($attributes['depends_on'])) {
            $info['depends'] = $attributes['depends_on'];
        }
        return $info;
    }

    /**
     * Returns name
     *
     * @return mixed|string
     */
    public function getName()
    {
        $attributes = $this->getAttributes();
        if (isset($attributes['name']) && !empty($attributes['name'])) {
            return $attributes['name'];
        }

        return 'element_'.$this->elementData['entity_id'];
    }

    /**
     * Returns Element ID
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->elementData['entity_id'];
    }

    /**
     * Returns element unique id
     *
     * @return mixed
     */
    public function getUid()
    {
        return $this->elementData['uid'];
    }

    /**
     * Replace variables to data
     *
     * @param $string
     * @return mixed
     */
    public function processFilters($string)
    {
        $source = $this->getSource();
        if ($source instanceof \Magento\Sales\Model\Order) {
            return $this->orderFilter->processFilter($this->getSource(), $string);
        } elseif ($source instanceof \Magento\Sales\Model\Order\Invoice) {
            return $this->invoiceFilter->processFilter($this->getSource(), $string);
        } elseif ($source instanceof \Magento\Sales\Model\Order\Creditmemo) {
            return $this->creditmemoFilter->processFilter($this->getSource(), $string);
        } elseif ($source instanceof \Magento\Sales\Model\Order\Shipment) {
            return $this->shipmentFilter->processFilter($this->getSource(), $string);
        }
        return $string;
    }

    /**
     * Returns Element Config
     *
     * @return array
     */
    public function getConfig()
    {
        if (empty($this->configClassName)) {
            return [];
        }

        return $this->objectManager->get($this->configClassName)->getConfig();
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getOrder()
    {
        if ($this->order == null) {
            $source = $this->getSource();
            if ($source instanceof \Magento\Sales\Model\Order) {
                $this->order = $source;
            } else {
                $this->order = $source->getOrder();
            }
        }

        return $this->order;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getFieldListToRemovePx()
    {
        return ['top', 'left', 'width', 'height'];
    }

    public function getOrderItem($item)
    {
        if ($item instanceof \Magento\Sales\Model\Order\Item) {
            return $item;
        }

        return $item->getOrderItem();
    }
}
