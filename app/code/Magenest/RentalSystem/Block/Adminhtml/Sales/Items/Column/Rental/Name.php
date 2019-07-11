<?php
/**
 * Created by PhpStorm.
 * User: ducanh
 * Date: 01/02/2019
 * Time: 08:35
 */

namespace Magenest\RentalSystem\Block\Adminhtml\Sales\Items\Column\Rental;

use Magenest\RentalSystem\Model\RentalOptionFactory;
use Magenest\RentalSystem\Model\RentalOptionTypeFactory;

class Name extends \Magento\Sales\Block\Adminhtml\Items\Column\Name
{

    /**
     * @var RentalOptionFactory
     */
    protected $rentalOptionFactory;

    /**
     * @var RentalOptionTypeFactory
     */
    protected $rentalOptionTypeFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timezone;

    /**
     * Name constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\Product\OptionFactory $optionFactory
     * @param RentalOptionFactory $rentalOptionFactory
     * @param RentalOptionTypeFactory $rentalOptionTypeFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product\OptionFactory $optionFactory,
        RentalOptionFactory $rentalOptionFactory,
        RentalOptionTypeFactory $rentalOptionTypeFactory,
        array $data = [])
    {
        $this->_timezone               = $context->getLocaleDate();
        $this->rentalOptionFactory     = $rentalOptionFactory;
        $this->rentalOptionTypeFactory = $rentalOptionTypeFactory;
        parent::__construct($context, $stockRegistry, $stockConfiguration, $registry, $optionFactory, $data);
    }

    /**
     * @param $option
     *
     * @return array
     */
    public function getOptionTitleAndPrice($option)
    {
        $option = explode('_', $option);

        $price = $option[0];
        $price = $this->getFormattedPrice($price);

        $optionId          = $option[2];
        $rentalOption      = $this->rentalOptionFactory->create()->getCollection()->addFieldToFilter('id', $optionId);
        $rentalOptionTitle = @$rentalOption->getData()[0]['option_title'];

        $optionTypeId          = $option[1];
        $rentalOptionType      = $this->rentalOptionTypeFactory->create()->getCollection()->addFieldToFilter('id', $optionTypeId);
        $rentalOptionTypeTitle = @$rentalOptionType->getData()[0]['option_title'];

        $type = $option[3];

        $optionTitleAndPrice = [
            'price'             => $price,
            'option_title'      => $rentalOptionTitle,
            'option_type_title' => $rentalOptionTypeTitle,
            'type'              => $type
        ];

        return $optionTitleAndPrice;
    }

    /**
     * @param $price
     *
     * @return mixed
     */
    public function getFormattedPrice($price)
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper    = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        $formattedPrice = $priceHelper->currency($price, true, false);

        return $formattedPrice;
    }

    /**
     * @param $time
     *
     * @return string
     */
    public function getLocateTime($time)
    {
        $str = $this->_timezone->formatDateTime($time, 3, 3);
        return $str;
    }

    /**
     * @return mixed
     */
    public function getRentalItem()
    {
        $orderid       = $this->getRequest()->getParam('order_id');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order         = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId((int)$orderid);
        $item          = $order->getAllItems();
        $item          = reset($item);

        return $item;
    }
}