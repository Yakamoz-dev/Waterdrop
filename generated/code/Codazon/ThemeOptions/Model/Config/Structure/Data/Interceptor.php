<?php
namespace Codazon\ThemeOptions\Model\Config\Structure\Data;

/**
 * Interceptor class for @see \Codazon\ThemeOptions\Model\Config\Structure\Data
 */
class Interceptor extends \Codazon\ThemeOptions\Model\Config\Structure\Data implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Codazon\ThemeOptions\Model\Config\Structure\Reader $reader, \Magento\Framework\Config\ScopeInterface $configScope, \Magento\Framework\Config\CacheInterface $cache, $cacheId)
    {
        $this->___init();
        parent::__construct($reader, $configScope, $cache, $cacheId);
    }

    /**
     * {@inheritdoc}
     */
    public function merge(array $config)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'merge');
        return $pluginInfo ? $this->___callPlugins('merge', func_get_args(), $pluginInfo) : parent::merge($config);
    }
}
