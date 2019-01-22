<?php
namespace Smile\ElasticsuiteCore\Model\ResourceModel\Search\Request\RelevanceConfig\Data\Collection;

/**
 * Factory class for @see \Smile\ElasticsuiteCore\Model\ResourceModel\Search\Request\RelevanceConfig\Data\Collection\Scoped
 */
class ScopedFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Smile\\ElasticsuiteCore\\Model\\ResourceModel\\Search\\Request\\RelevanceConfig\\Data\\Collection\\Scoped')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Smile\ElasticsuiteCore\Model\ResourceModel\Search\Request\RelevanceConfig\Data\Collection\Scoped
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
