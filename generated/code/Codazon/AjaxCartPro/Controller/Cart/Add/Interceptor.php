<?php
namespace Codazon\AjaxCartPro\Controller\Cart\Add;

/**
 * Interceptor class for @see \Codazon\AjaxCartPro\Controller\Cart\Add
 */
class Interceptor extends \Codazon\AjaxCartPro\Controller\Cart\Add implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Magento\Catalog\Helper\Output $outputHelper, \Magento\Catalog\Helper\Image $imgHelper, \Magento\Catalog\Model\Product\Url $productUrl, \Magento\Checkout\Model\Cart $cart, \Magento\Checkout\Helper\Data $checkoutHelper, \Magento\Catalog\Api\ProductRepositoryInterface $productRepository, \Codazon\AjaxCartPro\Controller\Product\Crosssell $crossell, \Magento\Checkout\CustomerData\Cart $cartData)
    {
        $this->___init();
        parent::__construct($context, $scopeConfig, $checkoutSession, $storeManager, $formKeyValidator, $outputHelper, $imgHelper, $productUrl, $cart, $checkoutHelper, $productRepository, $crossell, $cartData);
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
