<?php
namespace Ecopure\Gift\Controller\Adminhtml\Order\Save;

/**
 * Interceptor class for @see \Ecopure\Gift\Controller\Adminhtml\Order\Save
 */
class Interceptor extends \Ecopure\Gift\Controller\Adminhtml\Order\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Ecopure\Gift\Controller\Adminhtml\Order\PostDataProcessor $dataProcessor, \Ecopure\Gift\Model\Order $model, \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor)
    {
        $this->___init();
        parent::__construct($context, $dataProcessor, $model, $dataPersistor);
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
