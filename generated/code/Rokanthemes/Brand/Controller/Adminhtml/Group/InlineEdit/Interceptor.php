<?php
namespace Rokanthemes\Brand\Controller\Adminhtml\Group\InlineEdit;

/**
 * Interceptor class for @see \Rokanthemes\Brand\Controller\Adminhtml\Group\InlineEdit
 */
class Interceptor extends \Rokanthemes\Brand\Controller\Adminhtml\Group\InlineEdit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Cms\Api\PageRepositoryInterface $groupRepository, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory, \Rokanthemes\Brand\Model\Group $groupModel)
    {
        $this->___init();
        parent::__construct($context, $groupRepository, $jsonFactory, $groupModel);
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
