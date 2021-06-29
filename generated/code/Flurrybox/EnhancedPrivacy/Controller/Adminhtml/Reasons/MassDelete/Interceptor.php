<?php
namespace Flurrybox\EnhancedPrivacy\Controller\Adminhtml\Reasons\MassDelete;

/**
 * Interceptor class for @see \Flurrybox\EnhancedPrivacy\Controller\Adminhtml\Reasons\MassDelete
 */
class Interceptor extends \Flurrybox\EnhancedPrivacy\Controller\Adminhtml\Reasons\MassDelete implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Ui\Component\MassAction\Filter $filter, \Flurrybox\EnhancedPrivacy\Model\ResourceModel\Reason\CollectionFactory $reasonCollectionFactory, \Flurrybox\EnhancedPrivacy\Api\ReasonRepositoryInterface $reasonRepository)
    {
        $this->___init();
        parent::__construct($context, $filter, $reasonCollectionFactory, $reasonRepository);
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
