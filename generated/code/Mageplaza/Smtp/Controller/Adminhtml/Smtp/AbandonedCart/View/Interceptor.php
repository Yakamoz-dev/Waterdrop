<?php
namespace Mageplaza\Smtp\Controller\Adminhtml\Smtp\AbandonedCart\View;

/**
 * Interceptor class for @see \Mageplaza\Smtp\Controller\Adminhtml\Smtp\AbandonedCart\View
 */
class Interceptor extends \Mageplaza\Smtp\Controller\Adminhtml\Smtp\AbandonedCart\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \Mageplaza\Smtp\Model\AbandonedCartFactory $abandonedCartFactory, \Magento\Quote\Model\QuoteRepository $quoteRepository)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $registry, $abandonedCartFactory, $quoteRepository);
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
