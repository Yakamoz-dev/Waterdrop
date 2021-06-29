<?php
namespace Magento\Catalog\Helper\Product\Edit\Action\Attribute;

/**
 * Interceptor class for @see \Magento\Catalog\Helper\Product\Edit\Action\Attribute
 */
class Interceptor extends \Magento\Catalog\Helper\Product\Edit\Action\Attribute implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Framework\App\Route\Config $routeConfig, \Magento\Framework\Locale\ResolverInterface $locale, \Magento\Backend\Model\UrlInterface $backendUrl, \Magento\Backend\Model\Auth $auth, \Magento\Backend\App\Area\FrontNameResolver $frontNameResolver, \Magento\Framework\Math\Random $mathRandom, \Magento\Eav\Model\Config $eavConfig, \Magento\Backend\Model\Session $session, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productsFactory, \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->___init();
        parent::__construct($context, $routeConfig, $locale, $backendUrl, $auth, $frontNameResolver, $mathRandom, $eavConfig, $session, $productsFactory, $storeManager);
    }

    /**
     * {@inheritdoc}
     */
    public function getHomePageUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getHomePageUrl');
        return $pluginInfo ? $this->___callPlugins('getHomePageUrl', func_get_args(), $pluginInfo) : parent::getHomePageUrl();
    }
}
