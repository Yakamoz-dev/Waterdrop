<?php
namespace Codazon\GoogleAmpManager\Block\Widget\ProductsList;

/**
 * Interceptor class for @see \Codazon\GoogleAmpManager\Block\Widget\ProductsList
 */
class Interceptor extends \Codazon\GoogleAmpManager\Block\Widget\ProductsList implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility, \Magento\Framework\App\Http\Context $httpContext, \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder, \Magento\CatalogWidget\Model\Rule $rule, \Magento\Widget\Helper\Conditions $conditionsHelper, array $data = [], ?\Magento\Framework\Serialize\Serializer\Json $json = null, ?\Magento\Framework\View\LayoutFactory $layoutFactory = null, ?\Magento\Framework\Url\EncoderInterface $urlEncoder = null, ?\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository = null)
    {
        $this->___init();
        parent::__construct($context, $productCollectionFactory, $catalogProductVisibility, $httpContext, $sqlBuilder, $rule, $conditionsHelper, $data, $json, $layoutFactory, $urlEncoder, $categoryRepository);
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
