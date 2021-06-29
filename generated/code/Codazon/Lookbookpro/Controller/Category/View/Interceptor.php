<?php
namespace Codazon\Lookbookpro\Controller\Category\View;

/**
 * Interceptor class for @see \Codazon\Lookbookpro\Controller\Category\View
 */
class Interceptor extends \Codazon\Lookbookpro\Controller\Category\View implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Codazon\Lookbookpro\Model\LookbookCategoryFactory $categoryFactory, \Codazon\Lookbookpro\Model\LookbookFactory $lookbookFactory, \Magento\Framework\Registry $coreRegistry, \Codazon\Lookbookpro\Helper\Data $helper, \Magento\Framework\Registry $registry, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $categoryFactory, $lookbookFactory, $coreRegistry, $helper, $registry, $scopeConfig, $resultPageFactory, $resultForwardFactory);
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
