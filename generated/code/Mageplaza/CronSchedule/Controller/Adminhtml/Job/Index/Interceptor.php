<?php
namespace Mageplaza\CronSchedule\Controller\Adminhtml\Job\Index;

/**
 * Interceptor class for @see \Mageplaza\CronSchedule\Controller\Adminhtml\Job\Index
 */
class Interceptor extends \Mageplaza\CronSchedule\Controller\Adminhtml\Job\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Controller\Result\JsonFactory $jsonFactory, \Magento\Framework\Registry $registry, \Mageplaza\CronSchedule\Helper\Data $helper, \Mageplaza\CronSchedule\Model\JobFactory $jobFactory, \Magento\Cron\Model\ScheduleFactory $scheduleFactory, \Psr\Log\LoggerInterface $logger, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $jsonFactory, $registry, $helper, $jobFactory, $scheduleFactory, $logger, $cacheTypeList);
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
