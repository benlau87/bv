<?php
namespace Amazon\Payment\Api\PaymentManagementInterface;

/**
 * Proxy class for @see \Amazon\Payment\Api\PaymentManagementInterface
 */
class Proxy implements \Amazon\Payment\Api\PaymentManagementInterface, \Magento\Framework\ObjectManager\NoninterceptableInterface
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Proxied instance name
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Proxied instance
     *
     * @var \Amazon\Payment\Api\PaymentManagementInterface
     */
    protected $_subject = null;

    /**
     * Instance shareability flag
     *
     * @var bool
     */
    protected $_isShared = null;

    /**
     * Proxy constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     * @param bool $shared
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Amazon\\Payment\\Api\\PaymentManagementInterface', $shared = true)
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
        $this->_isShared = $shared;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['_subject', '_isShared', '_instanceName'];
    }

    /**
     * Retrieve ObjectManager from global scope
     */
    public function __wakeup()
    {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    /**
     * Clone proxied instance
     */
    public function __clone()
    {
        $this->_subject = clone $this->_getSubject();
    }

    /**
     * Get proxied instance
     *
     * @return \Amazon\Payment\Api\PaymentManagementInterface
     */
    protected function _getSubject()
    {
        if (!$this->_subject) {
            $this->_subject = true === $this->_isShared
                ? $this->_objectManager->get($this->_instanceName)
                : $this->_objectManager->create($this->_instanceName);
        }
        return $this->_subject;
    }

    /**
     * {@inheritdoc}
     */
    public function queuePendingCapture(\Amazon\Payment\Domain\Details\AmazonCaptureDetails $details, $paymentId, $orderId)
    {
        return $this->_getSubject()->queuePendingCapture($details, $paymentId, $orderId);
    }

    /**
     * {@inheritdoc}
     */
    public function queuePendingAuthorization(\Amazon\Payment\Domain\Details\AmazonAuthorizationDetails $details, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        return $this->_getSubject()->queuePendingAuthorization($details, $order);
    }

    /**
     * {@inheritdoc}
     */
    public function queuePendingRefund(\Amazon\Payment\Domain\Details\AmazonRefundDetails $details, \Magento\Payment\Model\InfoInterface $payment)
    {
        return $this->_getSubject()->queuePendingRefund($details, $payment);
    }

    /**
     * {@inheritdoc}
     */
    public function closeTransaction($transactionId, \Magento\Payment\Model\InfoInterface $payment, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        return $this->_getSubject()->closeTransaction($transactionId, $payment, $order);
    }

    /**
     * {@inheritdoc}
     */
    public function getTransaction($transactionId, \Magento\Payment\Model\InfoInterface $payment, \Magento\Sales\Api\Data\OrderInterface $order)
    {
        return $this->_getSubject()->getTransaction($transactionId, $payment, $order);
    }
}
