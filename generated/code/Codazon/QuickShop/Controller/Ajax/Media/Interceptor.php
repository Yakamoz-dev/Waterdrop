<?php
namespace Codazon\QuickShop\Controller\Ajax\Media;

/**
 * Interceptor class for @see \Codazon\QuickShop\Controller\Ajax\Media
 */
class Interceptor extends \Codazon\QuickShop\Controller\Ajax\Media implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Swatches\Helper\Data $swatchHelper, \Magento\Catalog\Model\ProductFactory $productModelFactory)
    {
        $this->___init();
        parent::__construct($context, $swatchHelper, $productModelFactory);
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
