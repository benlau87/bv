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

namespace Magetrend\PdfTemplates\Model;

use Braintree\Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magetrend\PdfTemplates\Model\ResourceModel\Element\Collection;

/**
 * Template model class
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class Template extends \Magento\Framework\Model\AbstractModel
{

    /**
     * @var \Magento\Framework\Filesystem
     */
    public $filesystem;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface|null
     */
    public $directory = null;

    /**
     * @var \Magento\Framework\Simplexml\Config|null
     */
    public $simpleXml = null;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadFactory
     */
    public $readFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $jsonHelper;

    /**
     * @var \Magento\Framework\Data\CollectionFactory
     */
    public $dataCollectionFactory;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    public $dataObjectFactory;

    /**
     * @var Pdf\Invoice
     */
    public $invoicePdf;

    /**
     * @var ResourceModel\Element\CollectionFactory
     */
    public $elementCollectionFactory;

    /**
     * @var ResourceModel\Attribute\CollectionFactory
     */
    public $attributeCollectionFactory;

    /**
     * @var array
     */
    public $pdfDocs = [];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $objectManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    public $emulation;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    public $repository;

    public $moduleHelper;


    /**
     * Template constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\CollectionFactory $dataCollectionFactory
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     * @param \Magento\Framework\Simplexml\Config $simpleXml
     * @param Pdf\Invoice $invoicePdf
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param ResourceModel\Element\CollectionFactory $elementCollectionFactory
     * @param ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Store\Model\App\Emulation $emulation
     * @param \Magento\Framework\View\Asset\Repository $repository
     * @param \Magetrend\PdfTemplates\Helper\Data $moduleHelper
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\CollectionFactory $dataCollectionFactory,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Magento\Framework\Simplexml\Config $simpleXml,
        \Magetrend\PdfTemplates\Model\Pdf\Invoice $invoicePdf,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magetrend\PdfTemplates\Model\ResourceModel\Element\CollectionFactory $elementCollectionFactory,
        \Magetrend\PdfTemplates\Model\ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\App\Emulation $emulation,
        \Magento\Framework\View\Asset\Repository $repository,
        \Magetrend\PdfTemplates\Helper\Data $moduleHelper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->filesystem = $filesystem;
        $this->readFactory = $readFactory;
        $this->simpleXml = $simpleXml;
        $this->directory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::ROOT);
        $this->objectManager = $objectManager;
        $this->invoicePdf = $invoicePdf;
        $this->storeManager = $storeManager;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->elementCollectionFactory = $elementCollectionFactory;
        $this->emulation = $emulation;
        $this->repository = $repository;
        $this->moduleHelper = $moduleHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    //@codingStandardsIgnoreLine
    protected function _construct()
    {
        $this->_init('Magetrend\PdfTemplates\Model\ResourceModel\Template');
    }

    /**
     * Returns design collection
     *
     * @param $type
     * @param $paperSize
     * @return array
     */
    public function getDesignCollection($type = 'invoice', $paperSize = '595_842')
    {
        $paperSize = rtrim(strtolower(str_replace(':', '_', $paperSize)), '_');
        $path = $this->moduleHelper->getModuleViewDirectory('/adminhtml/web/design/'.$type.'/'.$paperSize.'/');
        $fileList = $this->getDesignFileList($path);
        $collection = $this->dataCollectionFactory->create();
        if (empty($fileList)) {
            return [];
        }
        $path = rtrim($path, '/').'/';
        foreach ($fileList as $fileName) {
            $this->simpleXml->loadFile($path.$fileName);
            $designData = $this->jsonHelper->jsonEncode($this->simpleXml->getNode());
            $designData = $this->jsonHelper->jsonDecode($designData);
            $designObject = $this->dataObjectFactory->create()
                ->setData($designData);
            $collection->addItem($designObject);
        }

        return $collection;
    }

    /**
     * Returns pdf design file list
     *
     * @param $path
     * @return array
     */
    public function getDesignFileList($path)
    {
        $list = [];
        $fileList = $this->readFactory->create($path)->read();
        if (!empty($fileList)) {
            foreach ($fileList as $fileName) {
                $extension = explode('.', $fileName);
                if (in_array(strtolower(end($extension)), ['xml'])) {
                    $list[] = $fileName;
                }
            }
        }
        return $list;
    }

    /**
     * Generates and returns pdf
     *
     * @param array $invoices
     * @return \Zend_Pdf
     */
    public function getPdf($objects = [], $forceTemplateId = null)
    {
        if (empty($objects)) {
            return $this->invoicePdf->getEmptyDoc();
        }

        $currentStore = $this->storeManager->getStore()->getId();
        foreach ($objects as $object) {
            $this->emulation->startEnvironmentEmulation($object->getStoreId(),
                \Magento\Framework\App\Area::AREA_FRONTEND,
                true
            );

            $templateId = 0;
            if ($object instanceof \Magento\Sales\Model\Order) {
                $templateId = $this->moduleHelper
                    ->getPdfTemplateId('order', $object->getStoreId(), $object->getOrderId());
                $pdfProcessor = $this->objectManager->create('Magetrend\PdfTemplates\Model\Pdf\Order');
            } elseif ($object instanceof \Magento\Sales\Model\Order\Invoice) {
                $templateId = $this->moduleHelper
                    ->getPdfTemplateId('invoice', $object->getStoreId(), $object->getOrderId());
                $pdfProcessor = $this->objectManager->create('Magetrend\PdfTemplates\Model\Pdf\Invoice');
            } elseif ($object instanceof \Magento\Sales\Model\Order\Shipment) {
                $templateId = $this->moduleHelper
                    ->getPdfTemplateId('shipment', $object->getStoreId(), $object->getOrderId());
                $pdfProcessor = $this->objectManager->create('Magetrend\PdfTemplates\Model\Pdf\Shipment');
            } elseif ($object instanceof \Magento\Sales\Model\Order\Creditmemo) {
                $templateId = $this->moduleHelper
                    ->getPdfTemplateId('creditmemo', $object->getStoreId(), $object->getOrderId());
                $pdfProcessor = $this->objectManager->create('Magetrend\PdfTemplates\Model\Pdf\Creditmemo');
            } else {
                $templateId = 0;
            }

            if ($forceTemplateId !== null) {
                $templateId = $forceTemplateId;
            }

            if ($templateId > 0) {
                $this->load($templateId);
                $this->pdfDocs[] = $pdfProcessor->getPdf($object, $this);
            } else {
                $pdf = $this->getDefaultPdf($object);
                if ($pdf) {
                    $this->pdfDocs[] = $pdf;
                }
            }

            $this->emulation->stopEnvironmentEmulation();
        }
        return $this->mergeDocs();
    }

    /**
     * Merge invoices if there are more than one incoice
     *
     * @return mixed|null
     */
    public function mergeDocs()
    {
        if (empty($this->pdfDocs)) {
            return $this->invoicePdf->getEmptyDoc();
        }

        $merged = null;
        foreach ($this->pdfDocs as $pdf) {
            if ($merged == null) {
                $merged = $pdf;
            } else {
                foreach ($pdf->pages as $page) {
                    $merged->pages[] = $page;
                }
            }
        }

        return $merged;
    }

    public function createPdf($objects, $dir, $fileName, $templateId = null)
    {
        $path = $this->filesystem->getDirectoryRead($dir)->getAbsolutePath($fileName);
        $this->getPdf($objects, $templateId)->save($path);
        return $path;
    }

    /**
     * Create pdf
     *
     * @param $invoices
     * @param $dir
     * @param $fileName
     * @return string
     */
    public function createInvoicePdf($invoices, $dir, $fileName)
    {
        return $this->createInvoicePdf($invoices, $dir, $fileName);
    }

    /**
     * Export template
     *
     * @param $templateName
     * @return string
     */
    public function export($templateName)
    {
        $data = $this->getDataForExport();
        $xmlContent = $this->getXmlData($data);
        $fileName = preg_replace("/[^a-zA-Z0-9]+/", "", strtolower($templateName)).'_'.time().'.xml';
        $path = $this->filesystem->getDirectoryRead(DirectoryList::TMP)->getAbsolutePath($fileName);
        $writer = $this->filesystem->getDirectoryWrite(DirectoryList::TMP);
        $file = $writer->openFile($fileName, 'w');
        try {
            $file->lock();
            try {
                $file->write($xmlContent);
            } finally {
                $file->unlock();
            }
        } finally {
            $file->close();
        }

        return $fileName;
    }

    /**
     * Create template xml
     *
     * @param $data
     * @return string
     */
    public function getXmlData($data)
    {
        $fileContent = '<template>'."\n";
        foreach ($data['template'] as $key => $value) {
            $fileContent.= "\t".'<'.$key.'>'.$value.'</'.$key.'>'."\n";
        }
        $fileContent.= "\t".'<elements>'."\n";
        foreach ($data['elements'] as $element) {
            $fileContent.= "\t\t".'<element>'."\n";
            foreach ($element as $key => $value) {
                $fileContent.= "\t\t\t".'<'.$key.'>'.$value.'</'.$key.'>'."\n";
            }
            if (isset($data['attributes'][$element['entity_id']])) {
                $fileContent.= "\t\t\t".'<attributes>'."\n";
                $attributes = $data['attributes'][$element['entity_id']];
                foreach ($attributes as $attribute) {
                    $fileContent.= "\t\t\t\t".'<attribute>'."\n";
                    foreach ($attribute as $key => $value) {
                        $fileContent.= "\t\t\t\t\t".'<'.$key.'><![CDATA['.$value.']]></'.$key.'>'."\n";
                    }
                    $fileContent.= "\t\t\t\t".'</attribute>'."\n";
                }
                $fileContent.= "\t\t\t".'</attributes>'."\n";
            }
            $fileContent.= "\t\t".'</element>'."\n";
        }
        $fileContent.= "\t".'</elements>'."\n";
        $fileContent.= '</template>'."\n";
        return $fileContent;
    }

    /**
     * Returns template data for export
     * @return array
     */
    public function getDataForExport()
    {
        $groupedByElement = [];
        $elementIds = [];
        $elementsData = [];
        $templateData = $this->getData();

        $elements = $this->elementCollectionFactory->create()
            ->addFieldToFilter('template_id', $this->getId());

        if ($elements->getSize() > 0) {
            foreach ($elements as $element) {
                $elementIds[] = $element->getId();
                $elementsData[] = $element->getData();
            }
        }

        $attributes = $this->attributeCollectionFactory->create()
            ->addFieldToFilter('element_id', ['in' => $elementIds]);

        if ($attributes->getSize() > 0) {
            foreach ($attributes as $attribute) {
                if (!isset($groupedByElement[$attribute->getElementId()])) {
                    $groupedByElement[$attribute->getElementId()] = [];
                }
                $groupedByElement[$attribute->getElementId()][] = $attribute->getData();
            }
        }
        return [
            'template' => $templateData,
            'elements' => $elementsData,
            'attributes' => $groupedByElement
        ];
    }

    /**
     * Delete related objects
     */
    public function clean()
    {
        $elementIds = [];
        $elementCollection = $this->elementCollectionFactory->create()
            ->addFieldToFilter('template_id', $this->getId());

        if ($elementCollection->getSize() > 0) {
            foreach ($elementCollection as $element) {
                $elementIds[] = $element->getId();
            }
            $attributeCollection = $this->attributeCollectionFactory->create()
                ->addFieldToFilter('element_id', ['in' => $elementIds])
                ->walk('delete');
        }

        $elementCollection->walk('delete');
    }

    /**
     * Delete template
     *
     * @return $this
     */
    public function beforeDelete()
    {
        parent::beforeDelete();
        if ($this->getId()) {
            $this->clean();
        }
        return $this;
    }

    /**
     * Returns paper width in points
     * @return mixed
     */
    public function getPaperWidth()
    {
        $size = !empty($this->getSize())?$this->getSize():\Zend_Pdf_Page::SIZE_A4;
        list($width, $height) = explode(':', $size);
        return $width;
    }

    /**
     *  Returns paper height in points
     * @return mixed
     */
    public function getPaperHeight()
    {
        $size = !empty($this->getSize())?$this->getSize():\Zend_Pdf_Page::SIZE_A4;
        list($width, $height) = explode(':', $size);
        return $height;
    }

    public function getDefaultPdf($object)
    {
        if ($object instanceof \Magento\Sales\Model\Order\Invoice) {
            $pdfProcessor = $this->objectManager->create('Magento\Sales\Model\Order\Pdf\Invoice');
        } elseif ($object instanceof \Magento\Sales\Model\Order\Shipment) {
            $pdfProcessor = $this->objectManager->create('Magento\Sales\Model\Order\Pdf\Shipment');
        } elseif ($object instanceof \Magento\Sales\Model\Order\Creditmemo) {
            $pdfProcessor = $this->objectManager->create('Magento\Sales\Model\Order\Pdf\Creditmemo');
        } else {
            return false;
        }

        $this->_registry->register(\Magetrend\PdfTemplates\Helper\Data::REGISTRY_IGNORE_KEY, true, true);
        $pdf = $pdfProcessor->getPdf([$object]);
        $this->_registry->unregister(\Magetrend\PdfTemplates\Helper\Data::REGISTRY_IGNORE_KEY);
        return $pdf;
    }
}
