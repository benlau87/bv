<?php
namespace Smile\ElasticsuiteCore\Search\Adapter\Elasticsuite\Response;

/**
 * Factory class for @see \Smile\ElasticsuiteCore\Search\Adapter\Elasticsuite\Response\QueryResponse
 */
class QueryResponseFactory
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
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Smile\\ElasticsuiteCore\\Search\\Adapter\\Elasticsuite\\Response\\QueryResponse')
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Smile\ElasticsuiteCore\Search\Adapter\Elasticsuite\Response\QueryResponse
     */
    public function create(array $data = array())
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
