<?php
namespace Codazon\AjaxCartPro\Controller\Product\Wishlist\Remove;

/**
 * Interceptor class for @see \Codazon\AjaxCartPro\Controller\Product\Wishlist\Remove
 */
class Interceptor extends \Codazon\AjaxCartPro\Controller\Product\Wishlist\Remove implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, ?\Magento\Wishlist\Model\Product\AttributeValueProvider $attributeValueProvider, \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory)
    {
        $this->___init();
        parent::__construct($context, $wishlistProvider, $formKeyValidator, $attributeValueProvider, $resultLayoutFactory);
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
