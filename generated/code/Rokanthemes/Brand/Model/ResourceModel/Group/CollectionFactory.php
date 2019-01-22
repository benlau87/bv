<?php
namespace Rokanthemes\Brand\Model\ResourceModel\Group;

/**
 * Factory class for @see \Rokanthemes\Brand\Model\ResourceModel\Group\Collection
 */
class CollectionFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Rokanthemes\\Brand\\Model\\ResourceModel\\Group\\Collection')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Rokanthemes\Brand\Model\ResourceModel\Group\Collection
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
