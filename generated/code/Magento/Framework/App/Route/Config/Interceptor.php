<?php
namespace Magento\Framework\App\Route\Config;

/**
 * Interceptor class for @see \Magento\Framework\App\Route\Config
 */
class Interceptor extends \Magento\Framework\App\Route\Config implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Route\Config\Reader $reader, \Magento\Framework\Config\CacheInterface $cache, \Magento\Framework\Config\ScopeInterface $configScope, \Magento\Framework\App\AreaList $areaList, $cacheId = 'RoutesConfig')
    {
        $this->___init();
        parent::__construct($reader, $cache, $configScope, $areaList, $cacheId);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteByFrontName($frontName, $scope = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRouteByFrontName');
        return $pluginInfo ? $this->___callPlugins('getRouteByFrontName', func_get_args(), $pluginInfo) : parent::getRouteByFrontName($frontName, $scope);
    }
}
