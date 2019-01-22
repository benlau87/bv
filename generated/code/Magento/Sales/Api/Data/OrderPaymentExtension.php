<?php
namespace Magento\Sales\Api\Data;

/**
 * Extension class for @see \Magento\Sales\Api\Data\OrderPaymentInterface
 */
class OrderPaymentExtension extends \Magento\Framework\Api\AbstractSimpleObject implements OrderPaymentExtensionInterface
{
    /**
     * @return \Magento\Vault\Api\Data\PaymentTokenInterface|null
     */
    public function getVaultPaymentToken()
    {
        return $this->_get('vault_payment_token');
    }

    /**
     * @param \Magento\Vault\Api\Data\PaymentTokenInterface $vaultPaymentToken
     * @return $this
     */
    public function setVaultPaymentToken(\Magento\Vault\Api\Data\PaymentTokenInterface $vaultPaymentToken)
    {
        $this->setData('vault_payment_token', $vaultPaymentToken);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppReferenceNumber()
    {
        return $this->_get('ppp_reference_number');
    }

    /**
     * @param string $pppReferenceNumber
     * @return $this
     */
    public function setPppReferenceNumber($pppReferenceNumber)
    {
        $this->setData('ppp_reference_number', $pppReferenceNumber);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppInstructionType()
    {
        return $this->_get('ppp_instruction_type');
    }

    /**
     * @param string $pppInstructionType
     * @return $this
     */
    public function setPppInstructionType($pppInstructionType)
    {
        $this->setData('ppp_instruction_type', $pppInstructionType);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppPaymentDueDate()
    {
        return $this->_get('ppp_payment_due_date');
    }

    /**
     * @param string $pppPaymentDueDate
     * @return $this
     */
    public function setPppPaymentDueDate($pppPaymentDueDate)
    {
        $this->setData('ppp_payment_due_date', $pppPaymentDueDate);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppNote()
    {
        return $this->_get('ppp_note');
    }

    /**
     * @param string $pppNote
     * @return $this
     */
    public function setPppNote($pppNote)
    {
        $this->setData('ppp_note', $pppNote);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppBankName()
    {
        return $this->_get('ppp_bank_name');
    }

    /**
     * @param string $pppBankName
     * @return $this
     */
    public function setPppBankName($pppBankName)
    {
        $this->setData('ppp_bank_name', $pppBankName);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppAccountHolderName()
    {
        return $this->_get('ppp_account_holder_name');
    }

    /**
     * @param string $pppAccountHolderName
     * @return $this
     */
    public function setPppAccountHolderName($pppAccountHolderName)
    {
        $this->setData('ppp_account_holder_name', $pppAccountHolderName);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppInternationalBankAccountNumber()
    {
        return $this->_get('ppp_international_bank_account_number');
    }

    /**
     * @param string $pppInternationalBankAccountNumber
     * @return $this
     */
    public function setPppInternationalBankAccountNumber($pppInternationalBankAccountNumber)
    {
        $this->setData('ppp_international_bank_account_number', $pppInternationalBankAccountNumber);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppBankIdentifierCode()
    {
        return $this->_get('ppp_bank_identifier_code');
    }

    /**
     * @param string $pppBankIdentifierCode
     * @return $this
     */
    public function setPppBankIdentifierCode($pppBankIdentifierCode)
    {
        $this->setData('ppp_bank_identifier_code', $pppBankIdentifierCode);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppRoutingNumber()
    {
        return $this->_get('ppp_routing_number');
    }

    /**
     * @param string $pppRoutingNumber
     * @return $this
     */
    public function setPppRoutingNumber($pppRoutingNumber)
    {
        $this->setData('ppp_routing_number', $pppRoutingNumber);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppAmount()
    {
        return $this->_get('ppp_amount');
    }

    /**
     * @param string $pppAmount
     * @return $this
     */
    public function setPppAmount($pppAmount)
    {
        $this->setData('ppp_amount', $pppAmount);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPppCurrency()
    {
        return $this->_get('ppp_currency');
    }

    /**
     * @param string $pppCurrency
     * @return $this
     */
    public function setPppCurrency($pppCurrency)
    {
        $this->setData('ppp_currency', $pppCurrency);
        return $this;
    }
}
