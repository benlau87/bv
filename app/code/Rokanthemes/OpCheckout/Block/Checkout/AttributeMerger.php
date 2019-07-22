<?php

namespace Rokanthemes\OpCheckout\Block\Checkout;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Helper\Address as AddressHelper;
use Rokanthemes\OpCheckout\Helper\Config as OneStepConfig;
use Magento\Directory\Model\ResourceModel\Region\Collection as RegionCollection;

class AttributeMerger extends \Magento\Checkout\Block\Checkout\AttributeMerger
{

    protected $_oneStepConfig;
   
    protected $_regionCollection;
   
    protected $_directoryHelper;

    public function __construct(
        AddressHelper $addressHelper,
        Session $customerSession,
        CustomerRepository $customerRepository,
        DirectoryHelper $directoryHelper,
        OneStepConfig $oneStepConfig,
        RegionCollection $regionCollection
    )
    {
        $this->_oneStepConfig = $oneStepConfig;
        $this->_regionCollection = $regionCollection;
        $this->_directoryHelper = $directoryHelper;
        parent::__construct($addressHelper, $customerSession, $customerRepository, $directoryHelper);
    }

    protected function getDefaultValue($attributeCode): ?string
    {
        if ($this->_oneStepConfig->getFullRequest() == 'checkout_index_index') {
            switch ($attributeCode) {
                case 'firstname':
                    if ($this->getCustomer()) {
                        return $this->getCustomer()->getFirstname();
                    }
                    break;
                case 'lastname':
                    if ($this->getCustomer()) {
                        return $this->getCustomer()->getLastname();
                    }
                    break;
            }
            return null;
        } else {
            return parent::getDefaultValue($attributeCode);
        }
    }
}
