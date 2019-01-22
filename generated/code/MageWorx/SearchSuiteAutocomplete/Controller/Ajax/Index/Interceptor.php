<?php
namespace MageWorx\SearchSuiteAutocomplete\Controller\Ajax\Index;

/**
 * Interceptor class for @see \MageWorx\SearchSuiteAutocomplete\Controller\Ajax\Index
 */
class Interceptor extends \MageWorx\SearchSuiteAutocomplete\Controller\Ajax\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\MageWorx\SearchSuiteAutocomplete\Helper\Data $helperData, \Magento\Framework\App\Action\Context $context, \Magento\Search\Model\QueryFactory $queryFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \MageWorx\SearchSuiteAutocomplete\Model\Search $searchModel)
    {
        $this->___init();
        parent::__construct($helperData, $context, $queryFactory, $storeManager, $searchModel);
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
