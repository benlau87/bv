<?php
namespace Xtento\ProductExport\Model\System\Config\Source\Export;

/**
 * Factory class for @see \Xtento\ProductExport\Model\System\Config\Source\Export\Profile
 */
class ProfileFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Xtento\\ProductExport\\Model\\System\\Config\\Source\\Export\\Profile')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Xtento\ProductExport\Model\System\Config\Source\Export\Profile
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
