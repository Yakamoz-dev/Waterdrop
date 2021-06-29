<?php
namespace Mageplaza\CronSchedule\Controller\Adminhtml\Job\Run;

/**
 * Interceptor class for @see \Mageplaza\CronSchedule\Controller\Adminhtml\Job\Run
 */
class Interceptor extends \Mageplaza\CronSchedule\Controller\Adminhtml\Job\Run implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Mageplaza\CronSchedule\Model\Command $command)
    {
        $this->___init();
        parent::__construct($context, $command);
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
