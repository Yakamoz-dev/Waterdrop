<?php
namespace Codazon\ShippingCostCalculator\Controller\Index\Index;

/**
 * Interceptor class for @see \Codazon\ShippingCostCalculator\Controller\Index\Index
 */
class Interceptor extends \Codazon\ShippingCostCalculator\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory, \Codazon\ShippingCostCalculator\Helper\Data $helper, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository)
    {
        $this->___init();
        parent::__construct($context, $resultJsonFactory, $helper, $productRepository);
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
