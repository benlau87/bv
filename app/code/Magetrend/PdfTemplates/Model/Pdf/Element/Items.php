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

/**
 * Draw pdf element items
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class Items extends \Magetrend\PdfTemplates\Model\Pdf\Element\Table
{
    public $configClassName = 'Magetrend\PdfTemplates\Model\Pdf\Config\Items';

    /**
     * @var array
     */
    private $lines = [];

    /**
     * @var int
     */
    public $startFromItem = 0;

    /**
     * @var null
     */
    public $lastPageFooterHeight = null;

    /**
     * @var int
     */
    public $lastItemY = 0;

    /**
     * @var bool
     */
    public $isFinished = false;

    public $collectionSize = null;

    /**
     * Draw Pdf element
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

        $attributes = $this->getAttributes();
        if (empty($attributes)) {
            return $this;
        }

        $items = $this->getAllItems();
        $itemCount = count($items);
        $this->items = [];
        for ($i = $this->startFromItem; $i < $itemCount; $i++) {
            $this->items[] = $items[$i];
        }

        if ($currentPage > 1) {
            $this->elementData['attributes']['top'] = $this->template->getHeaderHeight();
            $this->attributes = null;
        }

        $this->drawHeader($currentPage);
        $this->setIsFinished($this->drawItems());
        $this->drawTableBorders();
        return $this;
    }

    /**
     * Draw items
     *
     * @return bool
     */
    public function drawItems()
    {
        $attributes = $this->getAttributes();
        $items = $this->getItems();
        $topY = $this->removePx($attributes['top'])
            + $this->removePx($attributes['table_header_height'])
            + $this->removePx($attributes['table_border_size']);
        $itemsCount = count($items);
        $i = 0;

        $lastVisibleItemKey = 0;
        foreach ($items as $key => $item) {
            if ($this->getOrderItem($item)->getParentItem()) {
                continue;
            }
            $lastVisibleItemKey = $key;
        }

        foreach ($items as $key => $item) {
            if ($this->getOrderItem($item)->getParentItem()) {
                $this->startFromItem++;
                continue;
            }
            $columnsData = $this->prepareColumnsData($item);
            $rowHeight = $this->getRowHeight($columnsData);

            if (!$this->isEnoughSpaceForItem($rowHeight, $topY, $lastVisibleItemKey==$key)) {
                return false;
            }

            $this->drawRowBackground($i, $topY, $rowHeight);
            $this->drawRowText($columnsData, $topY);

            if (isset($attributes['show_image']) && $attributes['show_image'] == 'true') {
                $this->drawProductImage($columnsData, $topY);
            }

            $topY += $rowHeight;
            $this->lastItemY = $topY;
            $this->startFromItem++;
            $i++;
        }

        return true;
    }

    /**
     * Prepare item data
     *
     * @param $item
     * @return array
     */
    public function prepareColumnsData($item)
    {
        $columnConfig = $this->getColumnConfig();
        $data = [];
        $attributes = $this->getAttributes();
        foreach ($columnConfig as $key => $column) {
            if ($this->isColumnHidden($key)) {
                continue;
            }

            if (!isset($column['renderer'])) {
                $column['renderer'] = 'Magetrend\PdfTemplates\Model\Pdf\Element\Items\Column\DefaultRenderer';
            }

            $data[$key] = $this->objectManager->get($column['renderer'])
                ->setData([
                    'item' => $item,
                    'order' => $this->order,
                    'item_renderer' => $this->getItemRenderer($item),
                    'column' => $key,
                    'attributes' => $attributes
                ])
                ->getPdfData();
        }

        return $data;
    }

    /**
     * Returns item options
     *
     * @param $item
     * @return string
     */
    public function getItemOptions($item)
    {
        $result = [];
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
        if (empty($result)) {
            return '';
        }
        $optionsString = '';
        foreach ($result as $option) {
            $optionsString.= $option['label'].': '.$option['value'].', ';
        }

        return rtrim($optionsString, ', ');
    }

    /**
     * Returns invoice items
     *
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Returns item renderer model
     *
     * @param $item
     * @return \Magetrend\PdfTemplates\Model\Pdf\Element\Items\Invoice\DefaultRenderer
     */
    public function getItemRenderer($item)
    {
        $orderItem = $this->getOrderItem($item);
        $oroductType = $orderItem->getProductType();

        switch ($oroductType) {
            case \Magento\Bundle\Model\Product\Type::TYPE_CODE:
                $renderer = $this->bundleItemRenderer;
                break;
            default:
                $renderer = $this->defaultItemRenderer;
                break;
        }

        $renderer->setItem($item)
            ->setOrder($this->getOrder());

        return $renderer;
    }

    public function getColumnConfig()
    {
        return $this->moduleHelper->getColumnConfig($this->template->getType());
    }

    /**
     * Get all items from source (invoice, shipment, creditmemo)
     * @return mixed
     */
    public function getAllItems()
    {
        return $this->getSource()->getAllItems();
    }

    /**
     * Returns items number
     * @return int|void
     */
    public function getCollectionSize()
    {
        if ($this->collectionSize == null) {
            $this->collectionSize = count($this->getAllItems());
        }

        return $this->collectionSize;
    }

    public function getFieldListToRemovePx()
    {
        $itemsConfig = $this->objectManager->get('Magetrend\PdfTemplates\Model\Pdf\Config\Items')->getConfig();
        $fields = [];
        foreach ($itemsConfig['attributes'] as $key => $attribute) {
            if (isset($attribute['format']) && in_array('remove_px', $attribute['format'])) {
                $fields[] = $key;
            }
        }

        return array_merge($fields, parent::getFieldListToRemovePx());
    }

    public function drawProductImage($columnsData, $topY)
    {
        $attributes = $this->getAttributes();
        $columnConfig = $this->getColumnConfig();
        $columnName = 'product';
        if (!isset($columnsData[$columnName]) || !isset($columnsData[$columnName]['image'])) {
            return;
        }

        $imageConfig = $columnsData[$columnName]['image'];
        $x = $attributes['left'] + $imageConfig['left'];
        $topY = $topY + $imageConfig['top'];

        try {
            $image = \Zend_Pdf_Image::imageWithPath($imageConfig['path']);
        } catch (\Zend_Pdf_Exception $e) {
            return;
        }

        $imageXY = $this->getImagePosition($topY, $x, $imageConfig['width'], $imageConfig['width']);
        $this->pdfPage->drawImage($image, $imageXY['x1'], $imageXY['y1'], $imageXY['x2'], $imageXY['y2']);

    }
}