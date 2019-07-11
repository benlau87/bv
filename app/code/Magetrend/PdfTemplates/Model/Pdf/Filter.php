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
 * Abstract variable filter class
 *
 * @category MageTrend
 * @package  Magetend/PdfTemplates
 * @author   Edvinas Stulpinas <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     https://www.magetrend.com/magento-2-pdf-invoice-pro
 */
abstract class Filter
{
    /**
     * @var \Magento\Sales\Model\AbstractModel
     */
    public $source;

    /**
     * @var \Magento\Sales\Model\Order
     */
    public $order = null;

    /**
     * @var \Magetrend\PdfTemplates\Helper\Data
     */
    public $moduleHelper;

    /**
     * @var \Magento\Payment\Helper\Data
     */
    public $paymentHelper;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    public $objectManager;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    public $countryFactory;

    /**
     * Returns entity data
     *
     * @return mixed
     */
    abstract public function getData();

    /**
     * Filter constructor.
     *
     * @param \Magetrend\PdfTemplates\Helper\Data $moduleHelper
     * @param \Magento\Payment\Helper\Data $paymentHelper
     */
    public function __construct(
        \Magetrend\PdfTemplates\Helper\Data $moduleHelper,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Directory\Model\CountryFactory $countryFactory
    ) {
        $this->moduleHelper = $moduleHelper;
        $this->paymentHelper = $paymentHelper;
        $this->objectManager = $objectManager;
        $this->countryFactory = $countryFactory;
    }

    /**
     * Replace variables to data from source object
     *
     * @param $source
     * @param $string
     * @return mixed
     */
    public function processFilter($source, $string)
    {
        $this->source = $source;
        $this->order = null;
        $variables = $this->getData();
        if (empty($variables)) {
            return $string;
        }

        foreach ($variables as $key => $value) {
            $string = str_replace('{'.$key.'}', $value, $string);
        }
        return $string;
    }

    /**
     * Returns source object
     *
     * @return \Magento\Sales\Model\AbstractModel
     */
    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Returns order object
     *
     * @return \Magento\Sales\Model\Order
     */
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

    /**
     * Returns grand total
     *
     * @return string
     */
    public function getGrandTotal()
    {
        return $this->getOrder()->formatPriceTxt($this->getSource()->getGrandTotal());
    }

    /**
     * Returns billing data
     *
     * @param $data
     * @return mixed
     */
    public function addBillingData($data)
    {
        $source = $this->getSource();
        $billingAddress = $source->getBillingAddress();
        $billingData = $billingAddress->getData();
        if (empty($billingData)) {
            return $data;
        }
        foreach ($billingData as $key => $value) {
            if (is_object($value)) {
                continue;
            }
            $data[$key] = $value;
        }
        $data['fullname'] = $billingAddress->getFirstname().' '.$billingAddress->getLastname();

        if (isset($data['country_id']) && !empty($data['country_id'])) {
            $country = $this->countryFactory->create()->loadByCode($data['country_id']);
            $data['country'] = $country->getName();
        }

        return $data;
    }

    /**
     * Returns billing data
     *
     * @param $data
     * @return mixed
     */
    public function addShippingData($data)
    {
        $source = $this->getSource();
        $shippingAddress = $source->getShippingAddress();
        if (!$shippingAddress) {
            return $data;
        }

        $shippingData = $shippingAddress->getData();
        if (empty($shippingData)) {
            return $data;
        }

        foreach ($shippingData as $key => $value) {
            if (is_object($value)) {
                continue;
            }
            $data['s_'.$key] = $value;
        }

        $data['s_fullname'] = $shippingAddress->getFirstname().' '.$shippingAddress->getLastname();

        if (isset($data['s_country_id']) && !empty($data['s_country_id'])) {
            $country = $this->countryFactory->create()->loadByCode($data['s_country_id']);
            $data['s_country'] = $country->getName();
        }
        return $data;
    }

    /**
     * Returns payment method information
     *
     * @param $data
     * @return mixed
     */
    public function addPaymentMethod($data)
    {
        $order = $this->getOrder();
        $payment = $order->getPayment();
        $method = $payment->getMethodInstance();
        $methodTitle = $method->getTitle();
        $data['payment_method'] = htmlspecialchars($methodTitle);
        $data['payment_additional'] = '';
        $paymentConfig = $this->moduleHelper->getPaymentConfig($payment->getMethod());
        if (isset($paymentConfig['renderer'])) {
            $data['payment_additional']  = $this->objectManager->get($paymentConfig['renderer'])
                ->setData([
                    'payment' => $payment,
                    'payment_instance' => $method,
                    'order' => $order
                ])
                ->getValue();
        }

        return $data;
    }

    /**
     * Returns payment method information
     *
     * @param $data
     * @return mixed
     */
    public function addShippingMethod($data)
    {
        $shippingDescription = $this->getOrder()->getShippingDescription();
        $data['shipping_method'] = htmlspecialchars($shippingDescription);
        return $data;
    }

    /**
     * Add comments
     *
     * @param $data
     * @return mixed
     */
    public function addComments($data)
    {
        $data['comment_label'] = '';
        $data['comment_text'] = '';

        $source = $this->getSource();
        if (!$source->getCommentsCollection() || $source->getCommentsCollection()->getSize() == 0) {
            return $data;
        }

        $commentsCollection = $source->getCommentsCollection();
        $comments = $commentsCollection->getItems();

        if (!empty($comments)) {
            $data['comment_label'] = (string)__('Notes:');
            foreach ($comments as $comment) {
                $commentText = $comment->getComment();
                $commentText = str_replace(["\n", '<br/>', '</br>', '<br>', '</p>'], '{br}', $commentText);
                $commentText = strip_tags($commentText);
                $data['comment_text'].=$commentText."{br} {br}";
            }
        }
        return $data;
    }
}
