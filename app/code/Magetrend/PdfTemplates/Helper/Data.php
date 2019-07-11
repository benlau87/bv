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

namespace Magetrend\PdfTemplates\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\GroupManagement;

/**
 * Module helper class
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Module status xml path
     */
    const XML_PATH_GENERAL_IS_ACTIVE = 'pdftemplates/general/is_active';

    const XML_PATH_GENERAL_DEV = 'pdftemplates/general/dev';

    const XML_PATH_TRANSLATE = 'pdftemplates/translate';

    const XML_PATH_CRON_TIMESTAMP = 'pdftemplates/cron/timestamp';

    const REGISTRY_IGNORE_KEY = 'mt_pdf_ignore';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var DirectoryList
     */
    public $directoryList;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    public $date;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $jsonHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Timezone
     */
    public $timeZone;

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    public $moduleReader;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    public $orderRepository;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param DirectoryList $directoryList
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\Stdlib\DateTime\Timezone $timezone
     * @param \Magento\Framework\Module\Dir\Reader $moduleReader
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        DirectoryList $directoryList,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\Stdlib\DateTime\Timezone $timezone,
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    ) {
        $this->storeManager = $storeManager;
        $this->directoryList = $directoryList;
        $this->date = $dateTime;
        $this->jsonHelper = $jsonHelper;
        $this->timeZone = $timezone;
        $this->moduleReader = $moduleReader;
        $this->orderRepository = $orderRepository;
        parent::__construct($context);
    }

    /**
     * Is module active in system config
     *
     * @param null $store
     * @return bool
     */
    public function isActive($store = null)
    {
        if ($this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_IS_ACTIVE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        )) {
            return true;
        }
        return false;
    }

    /**
     * Returns translated text
     *
     * @param string $keyWord
     * @param int $storeId
     * @return mixed
     */
    public function translate($keyWord, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_TRANSLATE . '/' . $keyWord,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Convert date to text
     *
     * @param $date
     * @param $messageBlock
     * @return \Magento\Framework\Phrase
     */
    public function getDateText($date, $messageBlock)
    {
        $beforeDateTime = strtotime($date);
        $currentDateTime = strtotime($this->date->gmtDate());
        $before1DayTime = strtotime("-1 day", $currentDateTime);
        if (date("d", $beforeDateTime) == date("d", $currentDateTime)) {
            $before = (int)(($currentDateTime - $beforeDateTime) / 60);
            if ($before <= 1) {
                return __($this->translate('before_one_minute'));
            } elseif ($before < 60) {
                return __($this->translate('before_x_minutes'), [$before]);
            } elseif ($before < (60 * 3)) {
                $beforeHour = (int)($before/60);
                if ($beforeHour == 1) {
                    return __($this->translate('before_one_hour'));
                } else {
                    return __($this->translate('before_x_hours'), [$beforeHour]);
                }
            } else {
                return __(
                    $this->translate('today_at'),
                    [$messageBlock->formatTime(date('h:i:s', $beforeDateTime))]
                );
            }
        } elseif (date("d", $beforeDateTime) == date("d", $before1DayTime)) {
            return __(
                $this->translate('yesterday_at'),
                [$messageBlock->formatTime(date('h:i:s', $beforeDateTime))]
            );
        } else {
            $beforeDays = (int)(($currentDateTime - $beforeDateTime) / (60*60*24));
            return __($this->translate('x_days_ago'), [$beforeDays]);
        }
    }

    /**
     * @param Y-m-d H:i:s string $date
     * @param $storeId
     * @param int $type
     * @return string
     */
    public function formatDate($date, $storeId, $type = \IntlDateFormatter::MEDIUM)
    {
        $localeCode = $this->scopeConfig
            ->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
        return $this->timeZone->formatDateTime($date, $type, \IntlDateFormatter::NONE, $localeCode);
    }

    /**
     * Is there created and selected template
     *
     * @param $type
     * @param int $storeId
     * @return bool
     */
    public function isTemplateChanged($type, $storeId = 0, $orderId = null)
    {
        return $this->getPdfTemplateId($type, $storeId, $orderId) > 0;
    }

    /**
     * Returns template id
     *
     * @param $type
     * @param int $storeId
     * @return bool
     */
    public function getPdfTemplateId($type, $storeId = 0, $orderId = null)
    {
        $usage = $this->scopeConfig->getValue(
            'pdftemplates/pdf/usage',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        if ($usage == 0) {
            $pdfTemplate = $this->scopeConfig->getValue(
                'pdftemplates/pdf/'.$type.'_template',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            );
            return $pdfTemplate;
        }

        $customerGroup = GroupManagement::CUST_GROUP_ALL; //32000 - all groups
        if (is_numeric($orderId) && $orderId > 0) {
            try {
                $order = $this->orderRepository->get($orderId);
                $customerGroup = $order->getCustomerGroupId();
            } catch (NoSuchEntityException $e) {
                $customerGroup = GroupManagement::CUST_GROUP_ALL;
            }
        }

        $templateCustormerGroupMap = $this->scopeConfig->getValue(
            'pdftemplates/pdf/'.$type.'_template_customer_group',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (empty($templateCustormerGroupMap)) {
            return 0;
        }

        if ($this->isSerialized($templateCustormerGroupMap)) {
            $map = unserialize($templateCustormerGroupMap);
        } else {
            $map = $this->jsonHelper->jsonDecode($templateCustormerGroupMap);
        }

        if (empty($map)) {
            return 0;
        }

        $customerGroupTemplate = [];
        foreach ($map as $option) {
            $customerGroupTemplate[$option['customer_group']] = $option['pdf_template'];
        }

        if (isset($customerGroupTemplate[$customerGroup])) {
            return $customerGroupTemplate[$customerGroup];
        }

        if (isset($customerGroupTemplate[GroupManagement::CUST_GROUP_ALL])) {
            return $customerGroupTemplate[GroupManagement::CUST_GROUP_ALL];
        }

        return 0;
    }

    /**
     * Return module installation dir
     * @param string $path
     * @return string
     */
    public function getModuleViewDirectory($path = '')
    {
        return rtrim($this->moduleReader->getModuleDir(
            \Magento\Framework\Module\Dir::MODULE_VIEW_DIR,
            'Magetrend_PdfTemplates'
        ), '/').'/'.trim($path, '/').'/';
    }

    /**
     * Write to log file
     *
     * @param $message
     */
    public function log($message)
    {
        $this->_logger->error($message);
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
                    $data[$key] = str_replace('px', '', $value);
                }
            } else {
                foreach ($fields as $key) {
                    if (!isset($data[$key])) {
                        continue;
                    }
                    $data[$key] = str_replace('px', '', $data[$key]);
                }
            }
        } else {
            $data = str_replace('px', '', $data);
        }
        return $data;
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
        $px = ((int)$px) * 72 / 96;
        return $px;
    }

    public function isEnabledOnFrontend($storeId = 0)
    {
        return $this->scopeConfig->getValue(
            'pdftemplates/general/frontend_is_active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getColumnConfig($type)
    {
        $columnConfig = $this->scopeConfig->getValue(
            'pdftemplates/items/columns',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            0
        );

        foreach ($columnConfig as $key => $column) {
            if (!isset($column['is_active'][$type]) || $column['is_active'][$type] != 1) {
                unset($columnConfig[$key]);
            }
        }

        return $columnConfig;
    }

    public function getPaymentConfig($paymentCode)
    {
        $config = $this->scopeConfig->getValue(
            'pdftemplates/payment',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            0
        );

        if (isset($config[$paymentCode])) {
            return $config[$paymentCode];
        }

        return [
            'renderer' => 'Magetrend\PdfTemplates\Model\Pdf\Element\Payment\DefaultRenderer'
        ];
    }

    public function getTrackColumnConfig()
    {
        $columnConfig = $this->scopeConfig->getValue(
            'pdftemplates/track_columns/columns',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            0
        );

        return $columnConfig;
    }

    public function getFolderBySize($size = \Zend_Pdf_Page::SIZE_A4)
    {
        return rtrim(strtolower(str_replace(':', '_', $size)), '_');
    }

    public function prepareFileName($fileName)
    {
        return str_replace('/', '_', $fileName);
    }

    private function isSerialized($value)
    {
        return (boolean) preg_match('/^((s|i|d|b|a|O|C):|N;)/', $value);
    }
}
