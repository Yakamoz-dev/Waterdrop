<?php
namespace Magento\ConfigurableProduct\Helper\Data;

/**
 * Interceptor class for @see \Magento\ConfigurableProduct\Helper\Data
 */
class Interceptor extends \Magento\ConfigurableProduct\Helper\Data implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Helper\Image $imageHelper, ?\Magento\Catalog\Model\Product\Image\UrlBuilder $urlBuilder = null)
    {
        $this->___init();
        parent::__construct($imageHelper, $urlBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function getGalleryImages(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getGalleryImages');
        return $pluginInfo ? $this->___callPlugins('getGalleryImages', func_get_args(), $pluginInfo) : parent::getGalleryImages($product);
    }
}
