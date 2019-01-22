<?php
namespace Benlau\MailOverride\Model\Email\Sender;

use Magento\Sales\Model\Order;

class OrderSender extends \Magento\Sales\Model\Order\Email\Sender\OrderSender
{
    protected function prepareTemplate(Order $order)
    {
        parent::prepareTemplate($order);

        //Get Payment Method
        $paymentMethod = $order->getPayment()->getMethod();

        //Define email template for each payment method
        switch ($paymentMethod) {
            case 'checkmo' : $templateId = 'custom_template_checkmo'; break;
            // Add cases if you have more payment methods
            default:
                $templateId = $order->getCustomerIsGuest() ?
                    $this->identityContainer->getGuestTemplateId()
                    : $this->identityContainer->getTemplateId();

        }

        $this->templateContainer->setTemplateId($templateId);
    }

}