<?php
namespace Magento\Framework\View\Asset\Repository;

/**
 * Interceptor class for @see \Magento\Framework\View\Asset\Repository
 */
class Interceptor extends \Magento\Framework\View\Asset\Repository implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\UrlInterface $baseUrl, \Magento\Framework\View\DesignInterface $design, \Magento\Framework\View\Design\Theme\ListInterface $themeList, \Magento\Framework\View\Asset\Source $assetSource, \Magento\Framework\App\Request\Http $request, \Magento\Framework\View\Asset\FileFactory $fileFactory, \Magento\Framework\View\Asset\File\FallbackContextFactory $fallbackContextFactory, \Magento\Framework\View\Asset\File\ContextFactory $contextFactory, \Magento\Framework\View\Asset\RemoteFactory $remoteFactory)
    {
        $this->___init();
        parent::__construct($baseUrl, $design, $themeList, $assetSource, $request, $fileFactory, $fallbackContextFactory, $contextFactory, $remoteFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function updateDesignParams(array &$params)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'updateDesignParams');
        return $pluginInfo ? $this->___callPlugins('updateDesignParams', func_get_args(), $pluginInfo) : parent::updateDesignParams($params);
    }

    /**
     * {@inheritdoc}
     */
    public function createAsset($fileId, array $params = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createAsset');
        return $pluginInfo ? $this->___callPlugins('createAsset', func_get_args(), $pluginInfo) : parent::createAsset($fileId, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function getStaticViewFileContext()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getStaticViewFileContext');
        return $pluginInfo ? $this->___callPlugins('getStaticViewFileContext', func_get_args(), $pluginInfo) : parent::getStaticViewFileContext();
    }

    /**
     * {@inheritdoc}
     */
    public function createSimilar($fileId, \Magento\Framework\View\Asset\LocalInterface $similarTo)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createSimilar');
        return $pluginInfo ? $this->___callPlugins('createSimilar', func_get_args(), $pluginInfo) : parent::createSimilar($fileId, $similarTo);
    }

    /**
     * {@inheritdoc}
     */
    public function createArbitrary($filePath, $dirPath, $baseDirType = 'static', $baseUrlType = 'static')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createArbitrary');
        return $pluginInfo ? $this->___callPlugins('createArbitrary', func_get_args(), $pluginInfo) : parent::createArbitrary($filePath, $dirPath, $baseDirType, $baseUrlType);
    }

    /**
     * {@inheritdoc}
     */
    public function createRelated($fileId, \Magento\Framework\View\Asset\LocalInterface $relativeTo)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createRelated');
        return $pluginInfo ? $this->___callPlugins('createRelated', func_get_args(), $pluginInfo) : parent::createRelated($fileId, $relativeTo);
    }

    /**
     * {@inheritdoc}
     */
    public function createRemoteAsset($url, $contentType)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createRemoteAsset');
        return $pluginInfo ? $this->___callPlugins('createRemoteAsset', func_get_args(), $pluginInfo) : parent::createRemoteAsset($url, $contentType);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($fileId)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUrl');
        return $pluginInfo ? $this->___callPlugins('getUrl', func_get_args(), $pluginInfo) : parent::getUrl($fileId);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlWithParams($fileId, array $params)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUrlWithParams');
        return $pluginInfo ? $this->___callPlugins('getUrlWithParams', func_get_args(), $pluginInfo) : parent::getUrlWithParams($fileId, $params);
    }
}
