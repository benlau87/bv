<?php
namespace GalacticLabs\CustomerGroupPaymentFilters\Model;

/**
 * Factory class for @see \GalacticLabs\CustomerGroupPaymentFilters\Model\PaymentFilter
 */
class PaymentFilterFactory
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $_instanceName = null;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\GalacticLabs\\CustomerGroupPaymentFilters\\Model\\PaymentFilter')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \GalacticLabs\CustomerGroupPaymentFilters\Model\PaymentFilter
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
