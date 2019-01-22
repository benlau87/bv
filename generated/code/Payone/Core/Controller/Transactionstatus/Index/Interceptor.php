<?php
namespace Payone\Core\Controller\Transactionstatus\Index;

/**
 * Interceptor class for @see \Payone\Core\Controller\Transactionstatus\Index
 */
class Interceptor extends \Payone\Core\Controller\Transactionstatus\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Payone\Core\Model\ResourceModel\TransactionStatus $transactionStatus, \Payone\Core\Helper\Toolkit $toolkitHelper, \Payone\Core\Helper\Environment $environmentHelper, \Payone\Core\Helper\Order $orderHelper, \Payone\Core\Model\TransactionStatus\Mapping $statusMapping, \Payone\Core\Model\TransactionStatus\Forwarding $statusForwarding, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory)
    {
        $this->___init();
        parent::__construct($context, $transactionStatus, $toolkitHelper, $environmentHelper, $orderHelper, $statusMapping, $statusForwarding, $resultRawFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
