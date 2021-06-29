<?php
namespace Magento\Backend\Model\Menu\Builder\Command\Remove;

/**
 * Interceptor class for @see \Magento\Backend\Model\Menu\Builder\Command\Remove
 */
class Interceptor extends \Magento\Backend\Model\Menu\Builder\Command\Remove implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(array $data = [])
    {
        $this->___init();
        parent::__construct($data);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $itemParams = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute($itemParams);
    }
}
