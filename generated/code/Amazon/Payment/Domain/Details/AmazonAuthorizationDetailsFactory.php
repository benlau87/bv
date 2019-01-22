<?php
namespace Amazon\Payment\Domain\Details;

/**
 * Factory class for @see \Amazon\Payment\Domain\Details\AmazonAuthorizationDetails
 */
class AmazonAuthorizationDetailsFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Amazon\\Payment\\Domain\\Details\\AmazonAuthorizationDetails')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Amazon\Payment\Domain\Details\AmazonAuthorizationDetails
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
