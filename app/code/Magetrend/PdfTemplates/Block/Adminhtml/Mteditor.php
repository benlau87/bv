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

namespace Magetrend\PdfTemplates\Block\Adminhtml;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Sales\Model\ResourceModel\Order\Invoice\Collection;

/**
 * MT Editor class
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class Mteditor extends \Magento\Backend\Block\Template
{
    const MEDIA_IMAGE_DIR = 'pdftemplates';

    /**
     * @var \Magento\Store\Model\System\Store
     */
    public $systemStore;

    /**
     * @var \Magento\Config\Model\Config\Source\Locale
     */
    public $locale = null;

    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    public $localeResolver;

    /**
     * @var \Magento\Email\Model\Template\Config
     */
    public $emailConfig;

    /**
     * @var \Magento\Framework\Registry
     */
    public $coreRegistry;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $objectManager;

    /**
     * @var
     */
    public $templateFilter = null;

    /**
     * @var \Magetrend\PdfTemplates\Helper\Data
     */
    public $helper;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadFactory
     */
    public $readFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    public $orderCollectionFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory
     */
    public $invoiceCollectionFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory
     */
    public $creditMemoCollectionFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory
     */
    public $shipmentCollectionFactory;

    /**
     * @var \Magetrend\PdfTemplates\Model\Template
     */
    public $template;

    /**
     * @var \Magetrend\PdfTemplates\Model\ResourceModel\Element\CollectionFactory
     */
    public $elementCollectionFactory;

    /**
     * @var \Magetrend\PdfTemplates\Model\ResourceModel\Attribute\CollectionFactory
     */
    public $attributeCollectionFactory;

    /**
     * @var \Magetrend\PdfTemplates\Model\Element
     */
    public $element;

    /**
     * @var null|array
     */
    public $elementList;

    public $paperSize;

    /**
     * Mteditor constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\Email\Model\Template\Config $emailConfig
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Config\Model\Config\Source\Locale $locale
     * @param \Magento\Framework\Locale\Resolver $localeResolver
     * @param \Magetrend\PdfTemplates\Helper\Data $helper
     * @param \Magetrend\PdfTemplates\Model\Template $template
     * @param \Magetrend\PdfTemplates\Model\Element $element
     * @param \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $invoiceCollectionFactory
     * @param \Magetrend\PdfTemplates\Model\ResourceModel\Element\CollectionFactory $elementCollectionFactory
     * @param \Magetrend\PdfTemplates\Model\ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $read
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Email\Model\Template\Config $emailConfig,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Config\Model\Config\Source\Locale $locale,
        \Magento\Framework\Locale\Resolver $localeResolver,
        \Magetrend\PdfTemplates\Helper\Data $helper,
        \Magetrend\PdfTemplates\Model\Template $template,
        \Magetrend\PdfTemplates\Model\Element $element,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $invoiceCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory $creditMemoCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\Shipment\CollectionFactory $shipmentCollectionFactory,
        \Magetrend\PdfTemplates\Model\ResourceModel\Element\CollectionFactory $elementCollectionFactory,
        \Magetrend\PdfTemplates\Model\ResourceModel\Attribute\CollectionFactory $attributeCollectionFactory,
        \Magento\Framework\Filesystem\Directory\ReadFactory $read,
        \Magetrend\PdfTemplates\Model\Config\Source\Size $paperSize,
        array $data = []
    ) {
        $this->systemStore = $systemStore;
        $this->locale = $locale;
        $this->localeResolver = $localeResolver;
        $this->emailConfig = $emailConfig;
        $this->coreRegistry = $coreRegistry;
        $this->objectManager = $objectManager;
        $this->helper = $helper;
        $this->readFactory = $read;
        $this->template = $template;
        $this->elementCollectionFactory = $elementCollectionFactory;
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->element = $element;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->invoiceCollectionFactory = $invoiceCollectionFactory;
        $this->creditMemoCollectionFactory = $creditMemoCollectionFactory;
        $this->shipmentCollectionFactory = $shipmentCollectionFactory;
        $this->paperSize = $paperSize;
        parent::__construct($context, $data);
    }

    /**
     * Returns config for javascript part
     *
     * @return array
     */
    public function getConfig()
    {
        $config = [
            'elements' => $this->getElementList(),
            'action' => $this->getActions(),
            'formKey' => $this->formKey->getFormKey(),
            'imageList' => $this->getImageList(),
            'template_id' => $this->getTemplateId(),
            'body' => [
                'css' => ''
            ],
            'contentHelper' => [],
            'template' => $this->getTemplateConfig(),
            'fontFamilyOptions' => $this->getFontFamilyOptionArray(),
            'color' => $this->getColorConfig(),

        ];

        $template = $this->getPdfTemplate();
        if (!$template) {
            return $config;
        }

        $config['data'] = $this->element->getElementsData($template->getId());
        $config['paperWidth'] = $this->getPdfTemplate()->getPaperWidth();
        $config['paperHeight'] = $this->getPdfTemplate()->getPaperHeight();
      
        return $config;
    }

    public function getColorConfig()
    {
        $elements = $this->getElementList();
        $colorScheme = [];
        foreach ($elements as $element) {
            foreach ($element['config']['attributes'] as $attribute) {
                if (isset($attribute['colorGroup'])) {
                    if (!isset($colorScheme[$attribute['colorGroup']])) {
                        $colorScheme[$attribute['colorGroup']] = [];
                    }
                    $colorScheme[$attribute['colorGroup']][] = $attribute;
                }
            }
        }
        return $colorScheme;
    }

    /**
     * Returns fonts list
     *
     * @return array
     */
    public function getFontFamilyOptionArray()
    {
        $fonts = $this->_scopeConfig->getValue('mtemail/mteditor/font');
        $fontsArray = explode("\n", $fonts);
        if (!empty($fontsArray)) {
            foreach ($fontsArray as $key => $value) {
                if (empty($value)) {
                    unset($fontsArray[$key]);
                }
            }
        }
        return $fontsArray;
    }

    /**
     * Returns template config data
     *
     * @return array
     */
    public function getTemplateConfig()
    {
        $template = $this->getPdfTemplate();
        if (!$template) {
            return [];
        }
        return $this->getPdfTemplate()->getData();
    }

    /**
     * Returns available image list
     *
     * @return array
     */
    public function getImageList()
    {
        $list = [];
        $path = $this->_filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            self::MEDIA_IMAGE_DIR
        );
        $baseUrl = $this->getStore()
            ->getBaseUrl('media').self::MEDIA_IMAGE_DIR.'/';
        $fileList = $this->readFactory->create($path)->read();

        if (!empty($fileList)) {
            foreach ($fileList as $fileName) {
                $extension = explode('.', $fileName);
                if (in_array(strtolower(end($extension)), ['jpg', 'png', 'jpeg', 'gif'])) {
                    $list[] = $baseUrl.$fileName;
                }
            }
        }
        return $list;
    }

    /**
     * Returns urls config
     *
     * @return array
     */
    public function getActions()
    {
        $template = $this->getPdfTemplate();
        $templateId = $template?$template->getId():0;
        $actions =  [
            'back' => $this->getUrl("pdftemplates/template/index"),
            'createTemplateUrl' => $this->getUrl("*/*/create/"),
            'initTemplateUrl' => $this->getUrl("*/*/template/"),
            'designList' => $this->getUrl("*/*/design/"),
            'uploadUrl' => $this->getUrl("*/*/upload/"),
            'saveUrl' => $this->getUrl("*/*/save/"),
            'preparePreviewAjaxUrl' => $this->getUrl("*/*/preparePreview/"),
            'previewUrl' => $this->getUrl("*/*/preview/", ['id' => $templateId]),
            'variableList' => $this->getUrl("*/*/variableList/", ['id' => $templateId]),
            'exportUrl' => $this->getUrl("*/*/export/", ['id' => $templateId]),
            'importUrl' => $this->getUrl("*/*/import/", ['id' => $templateId]),
            'sendTestEmilUrl' => $this->getUrl("*/*/send/"),
            'saveInfo' => $this->getUrl("*/*/saveInfo/"),
            'deleteTemplateAjax' => $this->getUrl("*/*/delete/"),
            'createNewBlock' => $this->getUrl("*/*/newBlock/"),
            'deleteBlock' => $this->getUrl("*/*/deleteBlock/"),
        ];
        return $actions;
    }

    /**
     * Returns config in json format
     * @return string
     */
    public function getJsonConfig()
    {
        return json_encode($this->getConfig());
    }

    /**
     * Returns available design list
     *
     * @return array
     */
    public function getTemplateList()
    {
        $collection = $this->template->getDesignCollection('invoice', \Zend_Pdf_Page::SIZE_A4);
        return $collection;
    }

    /**
     * Returns available paper size list
     *
     * @return array
     */
    public function getPaperSizeList()
    {
        $collection = $this->paperSize->toOptionArray();
        return $collection;
    }

    /**
     * Returns locale options
     *
     * @return array
     */
    public function getLocaleOptions()
    {
        return $this->locale->toOptionArray();
    }

    /**
     * Returns current locale
     *
     * @return array
     */
    public function getCurrentLocale()
    {
        return $this->localeResolver->getLocale();
    }

    /**
     * Returns store options
     *
     * @return array
     */
    public function getStoreOptions()
    {
        return $this->systemStore->getStoreValuesForForm(false, true);
    }

    /**
     * Returns template id
     *
     * @return array
     */
    public function getTemplateId()
    {
        $template = $this->getPdfTemplate();
        if (!$template) {
            return 0;
        }
        return $template->getId();
    }

    /**
     * Returns pdf template
     *
     * @return array
     */
    public function getPdfTemplate()
    {
        $template = $this->coreRegistry->registry('current_pdf_template');
        if (!$template || !$template->getId()) {
            return false;
        }

        return $template;
    }

    /**
     * Returns store id
     *
     * @return array
     */
    public function getStore()
    {
        $template = $this->getPdfTemplate();
        if (!$template || !$template->getId()) {
            return $this->_storeManager->getStore();
        }

        return $this->_storeManager->getStore($template->getStoreId());
    }

    /**
     * Returns element list
     *
     * @return array
     */
    public function getElementList()
    {
        if ($this->elementList == null) {
            $template = $this->getPdfTemplate();
            if (!$template) {
                return [];
            }

            $elementsBlock = $this->getChildBlock($template->getType().'_elements');

            $data = [];
            foreach ($elementsBlock->getChildNames() as $childName) {
                $element = $elementsBlock->getChildBlock($childName);
                $elementName = explode('.', $childName);
                $elementName = end($elementName);
                $data[$elementName] = [
                    'config' => $element->getConfig(),
                    'html' => $element->toHtml()
                ];
            }
            $this->elementList = $data;
        }

        return $this->elementList;
    }

    /**
     * Returns template type (invoice, shipment, creditmemo)
     *
     * @return mixed
     */
    public function getTemplateType()
    {
        $template = $this->getPdfTemplate();
        if (!$template) {
            return $this->getRequest()->getParam('type', 'general');
        }

        return $template->getType();
    }

    /**
     * Returns 20 latest invoice available for preview
     *
     * @return array
     */
    public function getAvailablePreviewObject()
    {
        switch ($this->getTemplateType()) {
            case 'order':
                $collection = $this->orderCollectionFactory->create()
                    ->setOrder('created_at', 'DESC')
                    ->setPageSize(20);
                break;
            case 'invoice':
                $collection = $this->invoiceCollectionFactory->create()
                    ->setOrder('created_at', 'DESC')
                    ->setPageSize(20);
                break;
            case 'shipment':
                $collection = $this->shipmentCollectionFactory->create()
                    ->setOrder('created_at', 'DESC')
                    ->setPageSize(20);
                break;
            case 'creditmemo':
                $collection = $this->creditMemoCollectionFactory->create()
                    ->setOrder('created_at', 'DESC')
                    ->setPageSize(20);
                break;
        }

        return $collection;
    }

    public function getPreviewTitle()
    {
        switch ($this->getTemplateType()) {
            case 'order':
                return (string)__('Order');
            case 'invoice':
                return (string)__('Invoice');
            case 'shipment':
                return (string)__('Shipment');
            case 'creditmemo':
                return (string)__('Credit Memo');
        }
    }
}
