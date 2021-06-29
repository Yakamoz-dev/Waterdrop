<?php
namespace Codazon\Lookbookpro\Controller\Adminhtml\LookbookCategory\Grid;

/**
 * Interceptor class for @see \Codazon\Lookbookpro\Controller\Adminhtml\LookbookCategory\Grid
 */
class Interceptor extends \Codazon\Lookbookpro\Controller\Adminhtml\LookbookCategory\Grid implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Controller\Result\RawFactory $resultRawFactory, \Magento\Framework\View\LayoutFactory $layoutFactory, \Magento\Framework\Registry $registry)
    {
        $this->___init();
        parent::__construct($context, $resultRawFactory, $layoutFactory, $registry);
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
