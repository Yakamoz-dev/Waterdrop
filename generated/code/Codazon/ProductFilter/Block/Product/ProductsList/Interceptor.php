<?php
namespace Codazon\ProductFilter\Block\Product\ProductsList;

/**
 * Interceptor class for @see \Codazon\ProductFilter\Block\Product\ProductsList
 */
class Interceptor extends \Codazon\ProductFilter\Block\Product\ProductsList implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Codazon\ProductFilter\Model\ResourceModel\Bestsellers\CollectionFactory $bestSellerCollectionFactory, \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, \Magento\Framework\App\Http\Context $httpContext, \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder, \Magento\CatalogWidget\Model\Rule $rule, \Magento\Widget\Helper\Conditions $conditionsHelper, \Magento\Catalog\Helper\ImageFactory $imageHelperFactory, \Magento\Framework\Url\Helper\Data $urlHelper, \Codazon\ProductFilter\Block\ImageBuilderFactory $customImageBuilderFactory, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $productCollectionFactory, $bestSellerCollectionFactory, $catalogProductVisibility, $httpContext, $sqlBuilder, $rule, $conditionsHelper, $imageHelperFactory, $urlHelper, $customImageBuilderFactory, $data);
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
