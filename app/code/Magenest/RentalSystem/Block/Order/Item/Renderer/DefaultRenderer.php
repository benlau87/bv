<?php
/**
 * Created by PhpStorm.
 * User: ducanh
 * Date: 30/01/2019
 * Time: 13:44
 */

namespace Magenest\RentalSystem\Block\Order\Item\Renderer;

use Magenest\RentalSystem\Model\RentalOptionFactory;
use Magenest\RentalSystem\Model\RentalOptionTypeFactory;

class DefaultRenderer extends \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer
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
     * DefaultRenderer constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory
     * @param RentalOptionFactory $rentalOptionFactory
     * @param RentalOptionTypeFactory $rentalOptionTypeFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        RentalOptionFactory $rentalOptionFactory,
        RentalOptionTypeFactory $rentalOptionTypeFactory,
        array $data = []
    ) {
        $this->rentalOptionFactory     = $rentalOptionFactory;
        $this->rentalOptionTypeFactory = $rentalOptionTypeFactory;
        parent::__construct($context, $string, $productOptionFactory, $data);
    }

    public function getOptionTitleAndPrice($option)
    {
        if (!empty($option)) {
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
        } else return '';
    }

    public function getFormattedPrice($price)
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper    = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        $formattedPrice = $priceHelper->currency($price, true, false);

        return $formattedPrice;
    }

}