<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\RentalSystem\Model\Config\Mail;

use Magento\Framework\DataObject;
use Magento\Framework\Option\ArrayInterface;
use Magento\Email\Model\ResourceModel\Template\CollectionFactory;
use Magento\Email\Model\Template\Config;

class Template extends DataObject implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $_templatesFactory;

    /**
     * @var Config
     */
    protected $_emailConfig;

    /**
     * Template constructor.
     *
     * @param CollectionFactory $templatesFactory
     * @param Config $emailConfig
     * @param array $data
     */
    public function __construct(
        CollectionFactory $templatesFactory,
        Config $emailConfig,
        array $data = []
    ) {
        parent::__construct($data);
        $this->_templatesFactory = $templatesFactory;
        $this->_emailConfig = $emailConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        /** @var $collection \Magento\Email\Model\ResourceModel\Template\Collection */
        $collection = $this->_templatesFactory->create();
        $collection->load();
        $options = $collection->toOptionArray();
        $array = [
            ['label' => __('Default Email Template'), 'value' => 'rental_email_template'],
        ];
        $select = array_merge($array, $options);

        return $select;
    }
}