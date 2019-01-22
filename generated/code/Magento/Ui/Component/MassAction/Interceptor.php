<?php
namespace Magento\Ui\Component\MassAction;

/**
 * Interceptor class for @see \Magento\Ui\Component\MassAction
 */
class Interceptor extends \Magento\Ui\Component\MassAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\UiComponent\ContextInterface $context, array $components = array(), array $data = array())
    {
        $this->___init();
        parent::__construct($context, $components, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'prepare');
        if (!$pluginInfo) {
            return parent::prepare();
        } else {
            return $this->___callPlugins('prepare', func_get_args(), $pluginInfo);
        }
    }
}
