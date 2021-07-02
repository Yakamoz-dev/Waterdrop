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
    public function getPageHelpUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPageHelpUrl');
        return $pluginInfo ? $this->___callPlugins('getPageHelpUrl', func_get_args(), $pluginInfo) : parent::getPageHelpUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function setPageHelpUrl($url = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setPageHelpUrl');
        return $pluginInfo ? $this->___callPlugins('setPageHelpUrl', func_get_args(), $pluginInfo) : parent::setPageHelpUrl($url);
    }

    /**
     * {@inheritdoc}
     */
    public function addPageHelpUrl($suffix)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addPageHelpUrl');
        return $pluginInfo ? $this->___callPlugins('addPageHelpUrl', func_get_args(), $pluginInfo) : parent::addPageHelpUrl($suffix);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($route = '', $params = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUrl');
        return $pluginInfo ? $this->___callPlugins('getUrl', func_get_args(), $pluginInfo) : parent::getUrl($route, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentUserId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCurrentUserId');
        return $pluginInfo ? $this->___callPlugins('getCurrentUserId', func_get_args(), $pluginInfo) : parent::getCurrentUserId();
    }

    /**
     * {@inheritdoc}
     */
    public function prepareFilterString($filterString)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'prepareFilterString');
        return $pluginInfo ? $this->___callPlugins('prepareFilterString', func_get_args(), $pluginInfo) : parent::prepareFilterString($filterString);
    }

    /**
     * {@inheritdoc}
     */
    public function generateResetPasswordLinkToken()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'generateResetPasswordLinkToken');
        return $pluginInfo ? $this->___callPlugins('generateResetPasswordLinkToken', func_get_args(), $pluginInfo) : parent::generateResetPasswordLinkToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getHomePageUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getHomePageUrl');
        return $pluginInfo ? $this->___callPlugins('getHomePageUrl', func_get_args(), $pluginInfo) : parent::getHomePageUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function getAreaFrontName($checkHost = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getAreaFrontName');
        return $pluginInfo ? $this->___callPlugins('getAreaFrontName', func_get_args(), $pluginInfo) : parent::getAreaFrontName($checkHost);
    }

    /**
     * {@inheritdoc}
     */
    public function isModuleOutputEnabled($moduleName = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isModuleOutputEnabled');
        return $pluginInfo ? $this->___callPlugins('isModuleOutputEnabled', func_get_args(), $pluginInfo) : parent::isModuleOutputEnabled($moduleName);
    }
}
