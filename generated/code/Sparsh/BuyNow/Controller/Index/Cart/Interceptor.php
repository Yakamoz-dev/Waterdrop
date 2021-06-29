<?php
namespace Sparsh\BuyNow\Controller\Index\Cart;

/**
 * Interceptor class for @see \Sparsh\BuyNow\Controller\Index\Cart
 */
class Interceptor extends \Sparsh\BuyNow\Controller\Index\Cart implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider, \Magento\Wishlist\Model\LocaleQuantityProcessor $quantityProcessor, \Magento\Wishlist\Model\ItemFactory $itemFactory, \Magento\Checkout\Model\Cart $cart, \Magento\Wishlist\Model\Item\OptionFactory $optionFactory, \Magento\Catalog\Helper\Product $productHelper, \Magento\Framework\Escaper $escaper, \Magento\Wishlist\Helper\Data $helper, \Magento\Checkout\Helper\Cart $cartHelper, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Sparsh\BuyNow\Helper\Data $buynowHelper)
    {
        $this->___init();
        parent::__construct($context, $wishlistProvider, $quantityProcessor, $itemFactory, $cart, $optionFactory, $productHelper, $escaper, $helper, $cartHelper, $formKeyValidator, $buynowHelper);
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
