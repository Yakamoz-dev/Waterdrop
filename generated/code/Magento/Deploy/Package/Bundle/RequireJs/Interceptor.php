<?php
namespace Magento\Deploy\Package\Bundle\RequireJs;

/**
 * Interceptor class for @see \Magento\Deploy\Package\Bundle\RequireJs
 */
class Interceptor extends \Magento\Deploy\Package\Bundle\RequireJs implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Filesystem $filesystem, \Magento\Deploy\Config\BundleConfig $bundleConfig, \Magento\Framework\View\Asset\Minification $minification, $area, $theme, $locale, array $contentPools = [])
    {
        $this->___init();
        parent::__construct($filesystem, $bundleConfig, $minification, $area, $theme, $locale, $contentPools);
    }

    /**
     * {@inheritdoc}
     */
    public function addFile($filePath, $sourcePath, $contentType)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addFile');
        return $pluginInfo ? $this->___callPlugins('addFile', func_get_args(), $pluginInfo) : parent::addFile($filePath, $sourcePath, $contentType);
    }
}
