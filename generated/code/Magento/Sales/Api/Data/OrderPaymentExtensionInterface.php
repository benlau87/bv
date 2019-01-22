<?php
namespace Magento\Sales\Api\Data;

/**
 * ExtensionInterface class for @see \Magento\Sales\Api\Data\OrderPaymentInterface
 */
interface OrderPaymentExtensionInterface extends \Magento\Framework\Api\ExtensionAttributesInterface
{
    /**
     * @return \Magento\Vault\Api\Data\PaymentTokenInterface|null
     */
    public function getVaultPaymentToken();

    /**
     * @param \Magento\Vault\Api\Data\PaymentTokenInterface $vaultPaymentToken
     * @return $this
     */
    public function setVaultPaymentToken(\Magento\Vault\Api\Data\PaymentTokenInterface $vaultPaymentToken);

    /**
     * @return string|null
     */
    public function getPppReferenceNumber();

    /**
     * @param string $pppReferenceNumber
     * @return $this
     */
    public function setPppReferenceNumber($pppReferenceNumber);

    /**
     * @return string|null
     */
    public function getPppInstructionType();

    /**
     * @param string $pppInstructionType
     * @return $this
     */
    public function setPppInstructionType($pppInstructionType);

    /**
     * @return string|null
     */
    public function getPppPaymentDueDate();

    /**
     * @param string $pppPaymentDueDate
     * @return $this
     */
    public function setPppPaymentDueDate($pppPaymentDueDate);

    /**
     * @return string|null
     */
    public function getPppNote();

    /**
     * @param string $pppNote
     * @return $this
     */
    public function setPppNote($pppNote);

    /**
     * @return string|null
     */
    public function getPppBankName();

    /**
     * @param string $pppBankName
     * @return $this
     */
    public function setPppBankName($pppBankName);

    /**
     * @return string|null
     */
    public function getPppAccountHolderName();

    /**
     * @param string $pppAccountHolderName
     * @return $this
     */
    public function setPppAccountHolderName($pppAccountHolderName);

    /**
     * @return string|null
     */
    public function getPppInternationalBankAccountNumber();

    /**
     * @param string $pppInternationalBankAccountNumber
     * @return $this
     */
    public function setPppInternationalBankAccountNumber($pppInternationalBankAccountNumber);

    /**
     * @return string|null
     */
    public function getPppBankIdentifierCode();

    /**
     * @param string $pppBankIdentifierCode
     * @return $this
     */
    public function setPppBankIdentifierCode($pppBankIdentifierCode);

    /**
     * @return string|null
     */
    public function getPppRoutingNumber();

    /**
     * @param string $pppRoutingNumber
     * @return $this
     */
    public function setPppRoutingNumber($pppRoutingNumber);

    /**
     * @return string|null
     */
    public function getPppAmount();

    /**
     * @param string $pppAmount
     * @return $this
     */
    public function setPppAmount($pppAmount);

    /**
     * @return string|null
     */
    public function getPppCurrency();

    /**
     * @param string $pppCurrency
     * @return $this
     */
    public function setPppCurrency($pppCurrency);
}
