<?php
namespace Codazon\ThemeOptions\Controller\Adminhtml\Config\Edit;

/**
 * Interceptor class for @see \Codazon\ThemeOptions\Controller\Adminhtml\Config\Edit
 */
class Interceptor extends \Codazon\ThemeOptions\Controller\Adminhtml\Config\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Codazon\ThemeOptions\Model\Config\Structure $configStructure, \Codazon\ThemeOptions\Model\Config $backendConfig, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\App\State $state)
    {
        $this->___init();
        parent::__construct($context, $configStructure, $backendConfig, $resultPageFactory, $registry, $scopeConfig, $state);
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
