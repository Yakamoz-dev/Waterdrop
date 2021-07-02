<?php
namespace Magento\Catalog\Helper\Image;

/**
 * Interceptor class for @see \Magento\Catalog\Helper\Image
 */
class Interceptor extends \Magento\Catalog\Helper\Image implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Helper\Context $context, \Magento\Catalog\Model\Product\ImageFactory $productImageFactory, \Magento\Framework\View\Asset\Repository $assetRepo, \Magento\Framework\View\ConfigInterface $viewConfig, ?\Magento\Catalog\Model\View\Asset\PlaceholderFactory $placeholderFactory = null, ?\Magento\Catalog\Model\Config\CatalogMediaConfig $mediaConfig = null)
    {
        $this->___init();
        parent::__construct($context, $productImageFactory, $assetRepo, $viewConfig, $placeholderFactory, $mediaConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function init($product, $imageId, $attributes = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'init');
        return $pluginInfo ? $this->___callPlugins('init', func_get_args(), $pluginInfo) : parent::init($product, $imageId, $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function resize($width, $height = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'resize');
        return $pluginInfo ? $this->___callPlugins('resize', func_get_args(), $pluginInfo) : parent::resize($width, $height);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuality($quality)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setQuality');
        return $pluginInfo ? $this->___callPlugins('setQuality', func_get_args(), $pluginInfo) : parent::setQuality($quality);
    }

    /**
     * {@inheritdoc}
     */
    public function keepAspectRatio($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'keepAspectRatio');
        return $pluginInfo ? $this->___callPlugins('keepAspectRatio', func_get_args(), $pluginInfo) : parent::keepAspectRatio($flag);
    }

    /**
     * {@inheritdoc}
     */
    public function keepFrame($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'keepFrame');
        return $pluginInfo ? $this->___callPlugins('keepFrame', func_get_args(), $pluginInfo) : parent::keepFrame($flag);
    }

    /**
     * {@inheritdoc}
     */
    public function keepTransparency($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'keepTransparency');
        return $pluginInfo ? $this->___callPlugins('keepTransparency', func_get_args(), $pluginInfo) : parent::keepTransparency($flag);
    }

    /**
     * {@inheritdoc}
     */
    public function constrainOnly($flag)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'constrainOnly');
        return $pluginInfo ? $this->___callPlugins('constrainOnly', func_get_args(), $pluginInfo) : parent::constrainOnly($flag);
    }

    /**
     * {@inheritdoc}
     */
    public function backgroundColor($colorRGB)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'backgroundColor');
        return $pluginInfo ? $this->___callPlugins('backgroundColor', func_get_args(), $pluginInfo) : parent::backgroundColor($colorRGB);
    }

    /**
     * {@inheritdoc}
     */
    public function rotate($angle)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'rotate');
        return $pluginInfo ? $this->___callPlugins('rotate', func_get_args(), $pluginInfo) : parent::rotate($angle);
    }

    /**
     * {@inheritdoc}
     */
    public function watermark($fileName, $position, $size = null, $imageOpacity = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'watermark');
        return $pluginInfo ? $this->___callPlugins('watermark', func_get_args(), $pluginInfo) : parent::watermark($fileName, $position, $size, $imageOpacity);
    }

    /**
     * {@inheritdoc}
     */
    public function placeholder($fileName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'placeholder');
        return $pluginInfo ? $this->___callPlugins('placeholder', func_get_args(), $pluginInfo) : parent::placeholder($fileName);
    }

    /**
     * {@inheritdoc}
     */
    public function getPlaceholder($placeholder = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPlaceholder');
        return $pluginInfo ? $this->___callPlugins('getPlaceholder', func_get_args(), $pluginInfo) : parent::getPlaceholder($placeholder);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getUrl');
        return $pluginInfo ? $this->___callPlugins('getUrl', func_get_args(), $pluginInfo) : parent::getUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'save');
        return $pluginInfo ? $this->___callPlugins('save', func_get_args(), $pluginInfo) : parent::save();
    }

    /**
     * {@inheritdoc}
     */
    public function getResizedImageInfo()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getResizedImageInfo');
        return $pluginInfo ? $this->___callPlugins('getResizedImageInfo', func_get_args(), $pluginInfo) : parent::getResizedImageInfo();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultPlaceholderUrl($placeholder = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDefaultPlaceholderUrl');
        return $pluginInfo ? $this->___callPlugins('getDefaultPlaceholderUrl', func_get_args(), $pluginInfo) : parent::getDefaultPlaceholderUrl($placeholder);
    }

    /**
     * {@inheritdoc}
     */
    public function setWatermarkSize($size)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWatermarkSize');
        return $pluginInfo ? $this->___callPlugins('setWatermarkSize', func_get_args(), $pluginInfo) : parent::setWatermarkSize($size);
    }

    /**
     * {@inheritdoc}
     */
    public function setWatermarkImageOpacity($imageOpacity)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setWatermarkImageOpacity');
        return $pluginInfo ? $this->___callPlugins('setWatermarkImageOpacity', func_get_args(), $pluginInfo) : parent::setWatermarkImageOpacity($imageOpacity);
    }

    /**
     * {@inheritdoc}
     */
    public function setImageFile($file)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setImageFile');
        return $pluginInfo ? $this->___callPlugins('setImageFile', func_get_args(), $pluginInfo) : parent::setImageFile($file);
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalWidth()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOriginalWidth');
        return $pluginInfo ? $this->___callPlugins('getOriginalWidth', func_get_args(), $pluginInfo) : parent::getOriginalWidth();
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalHeight()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOriginalHeight');
        return $pluginInfo ? $this->___callPlugins('getOriginalHeight', func_get_args(), $pluginInfo) : parent::getOriginalHeight();
    }

    /**
     * {@inheritdoc}
     */
    public function getOriginalSizeArray()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getOriginalSizeArray');
        return $pluginInfo ? $this->___callPlugins('getOriginalSizeArray', func_get_args(), $pluginInfo) : parent::getOriginalSizeArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getType');
        return $pluginInfo ? $this->___callPlugins('getType', func_get_args(), $pluginInfo) : parent::getType();
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getWidth');
        return $pluginInfo ? $this->___callPlugins('getWidth', func_get_args(), $pluginInfo) : parent::getWidth();
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getHeight');
        return $pluginInfo ? $this->___callPlugins('getHeight', func_get_args(), $pluginInfo) : parent::getHeight();
    }

    /**
     * {@inheritdoc}
     */
    public function getFrame()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFrame');
        return $pluginInfo ? $this->___callPlugins('getFrame', func_get_args(), $pluginInfo) : parent::getFrame();
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getLabel');
        return $pluginInfo ? $this->___callPlugins('getLabel', func_get_args(), $pluginInfo) : parent::getLabel();
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
