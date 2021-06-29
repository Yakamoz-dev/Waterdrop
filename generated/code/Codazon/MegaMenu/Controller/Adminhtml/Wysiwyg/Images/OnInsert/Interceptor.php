<?php
namespace Codazon\MegaMenu\Controller\Adminhtml\Wysiwyg\Images\OnInsert;

/**
 * Interceptor class for @see \Codazon\MegaMenu\Controller\Adminhtml\Wysiwyg\Images\OnInsert
 */
class Interceptor extends \Codazon\MegaMenu\Controller\Adminhtml\Wysiwyg\Images\OnInsert implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\Controller\Result\Json $resultJsonFactory)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $resultJsonFactory);
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
