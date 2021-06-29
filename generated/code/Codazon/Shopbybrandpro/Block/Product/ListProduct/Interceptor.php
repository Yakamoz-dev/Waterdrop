<?php
namespace Codazon\Shopbybrandpro\Block\Product\ListProduct;

/**
 * Interceptor class for @see \Codazon\Shopbybrandpro\Block\Product\ListProduct
 */
class Interceptor extends \Codazon\Shopbybrandpro\Block\Product\ListProduct implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Data\Helper\PostHelper $postDataHelper, \Magento\Catalog\Model\Layer\Resolver $layerResolver, \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository, \Magento\Framework\Url\Helper\Data $urlHelper, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        return $pluginInfo ? $this->___callPlugins('getImage', func_get_args(), $pluginInfo) : parent::getImage($product, $imageId, $attributes);
    }
}
