<?php
namespace Magento\Backend\Helper\Data;

/**
 * Interceptor class for @see \Magento\Backend\Helper\Data
 */
class Interceptor extends \Magento\Backend\Helper\Data implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Framework\App\Route\Config $routeConfig, \Magento\Framework\Locale\ResolverInterface $locale, \Magento\Backend\Model\UrlInterface $backendUrl, \Magento\Backend\Model\Auth $auth, \Magento\Backend\App\Area\FrontNameResolver $frontNameResolver, \Magento\Framework\Math\Random $mathRandom)
    {
        $this->___init();
        parent::__construct($context, $routeConfig, $locale, $backendUrl, $auth, $frontNameResolver, $mathRandom);
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
