<?php
namespace Codazon\ProductFilter\Controller\Index\FirstLoad;

/**
 * Interceptor class for @see \Codazon\ProductFilter\Controller\Index\FirstLoad
 */
class Interceptor extends \Codazon\ProductFilter\Controller\Index\FirstLoad implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Codazon\ProductFilter\Block\Product\FirstLoad $productsListBlock, \Magento\Framework\App\CacheInterface $cache, \Magento\Framework\App\Cache\StateInterface $cacheStage)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $productsListBlock, $cache, $cacheStage);
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
