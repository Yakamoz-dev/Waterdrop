<?php
namespace Mageplaza\Reports\Controller\Adminhtml\Dashboard\SwitchToNew;

/**
 * Interceptor class for @see \Mageplaza\Reports\Controller\Adminhtml\Dashboard\SwitchToNew
 */
class Interceptor extends \Mageplaza\Reports\Controller\Adminhtml\Dashboard\SwitchToNew implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\App\Config\Storage\Writer $storageWriter, \Magento\Framework\App\Cache\TypeListInterface $typeList)
    {
        $this->___init();
        parent::__construct($context, $storageWriter, $typeList);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute();
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }
}
