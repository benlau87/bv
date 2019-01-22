<?php
namespace Magento\Customer\Api\Data;

/**
 * Extension class for @see \Magento\Customer\Api\Data\GroupInterface
 */
class GroupExtension extends \Magento\Framework\Api\AbstractSimpleObject implements GroupExtensionInterface
{
    /**
     * @return \GalacticLabs\CustomerGroupPaymentFilters\Api\Data\PaymentFilterInterface|null
     */
    public function getDisallowedPaymentOptions()
    {
        return $this->_get('disallowed_payment_options');
    }

    /**
     * @param \GalacticLabs\CustomerGroupPaymentFilters\Api\Data\PaymentFilterInterface $disallowedPaymentOptions
     * @return $this
     */
    public function setDisallowedPaymentOptions(\GalacticLabs\CustomerGroupPaymentFilters\Api\Data\PaymentFilterInterface $disallowedPaymentOptions)
    {
        $this->setData('disallowed_payment_options', $disallowedPaymentOptions);
        return $this;
    }
}
