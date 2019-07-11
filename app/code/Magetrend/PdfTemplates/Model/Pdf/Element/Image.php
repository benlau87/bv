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

namespace Magetrend\PdfTemplates\Model\Pdf\Element;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Draw pdf element image
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class Image extends \Magetrend\PdfTemplates\Model\Pdf\Element
{
    public $configClassName = 'Magetrend\PdfTemplates\Model\Pdf\Config\Image';
    /**
     * Draw element
     *
     * @param $pdfPage
     * @param $elemetData
     * @param $source
     * @param $template
     * @param $elements
     * @param $currentPage
     * @return $this
     */
    public function draw($pdfPage, $elemetData, $source, $template, $elements, $currentPage)
    {
        parent::draw($pdfPage, $elemetData, $source, $template, $elements, $currentPage);
        $this->drawImage();
        return $this;
    }

    /**
     * Draw image
     */
    public function drawImage()
    {
        $attributes = $this->getAttributes();
        $imageXY = $this->getImagePosition(
            $attributes['top'],
            $attributes['left'],
            $attributes['width'],
            $attributes['height']
        );

        try {
            $image = \Zend_Pdf_Image::imageWithPath($this->getImagePath($attributes['src']));
        } catch (\Zend_Pdf_Exception $e) {
            return;
        }

        if (isset($attributes['background_color'])) {
            $top = $this->getTopPosition(
                $attributes['top'],
                $attributes['left']
            );
            $bottom = $this->getBottomPosition(
                $attributes['top'],
                $attributes['left'],
                $attributes['width'],
                $attributes['height']
            );
            $color = $this->getPdfColor($attributes['background_color']);
            $this->pdfPage->setFillColor($color);
            $this->pdfPage->setLineColor($color);

            $this->pdfPage->setLineWidth(0);
            $this->pdfPage->drawRectangle(
                $imageXY['x1'],
                $imageXY['y1'],
                $imageXY['x2'],
                $imageXY['y2'],
                \Zend_Pdf_Page::SHAPE_DRAW_FILL
            );
        }

        $this->pdfPage->drawImage($image, $imageXY['x1'], $imageXY['y1'], $imageXY['x2'], $imageXY['y2']);
    }

    /**
     * Returns image path
     * @param $imgUrl
     * @return string
     */
    public function getImagePath($imgUrl)
    {
        if (substr_count($imgUrl, 'media/pdftemplates') == 1) {
            $path = $this->fileSystem->getDirectoryRead(DirectoryList::PUB)->getAbsolutePath('media/pdftemplates');
            $imgUrl = explode('media/pdftemplates', $imgUrl);
            $imgPath = rtrim($path, '/').$imgUrl[1];
        } else {
            $file = $this->repository->createAsset(
                'Magetrend_PdfTemplates::images/media/logo.jpg',
                ['area' => 'adminhtml']
            )->getRelativeSourceFilePath();
            $path = $this->fileSystem->getDirectoryRead(DirectoryList::PUB)->getAbsolutePath('static/'.$file);
            $path = explode('Magetrend_PdfTemplates', $path);
            $imgUrl = explode('Magetrend_PdfTemplates', $imgUrl);
            if (isset($imgUrl[1])) {
                $imgPath = $path[0].'Magetrend_PdfTemplates'.$imgUrl[1];
            } else {
                $imgPath = '';
            }
        }

        return $imgPath;
    }
}
