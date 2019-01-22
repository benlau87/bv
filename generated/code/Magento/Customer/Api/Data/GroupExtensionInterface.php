<?php
namespace Magento\Customer\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Customer\Api\Data\GroupInterface
 */
interface GroupExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \GalacticLabs\CustomerGroupPaymentFilters\Api\Data\PaymentFilterInterface|null
     */
    public function getDisallowedPaymentOptions();

    /**
     * @param \GalacticLabs\CustomerGroupPaymentFilters\Api\Data\PaymentFilterInterface $disallowedPaymentOptions
     * @return $this
     */
    public function setDisallowedPaymentOptions(\GalacticLabs\CustomerGroupPaymentFilters\Api\Data\PaymentFilterInterface $disallowedPaymentOptions);
}
