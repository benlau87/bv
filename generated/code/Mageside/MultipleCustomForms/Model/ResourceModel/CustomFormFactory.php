<?php
namespace Mageside\MultipleCustomForms\Model\ResourceModel;

/**
 * Factory class for @see \Mageside\MultipleCustomForms\Model\ResourceModel\CustomForm
 */
class CustomFormFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Mageside\\MultipleCustomForms\\Model\\ResourceModel\\CustomForm')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Mageside\MultipleCustomForms\Model\ResourceModel\CustomForm
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
