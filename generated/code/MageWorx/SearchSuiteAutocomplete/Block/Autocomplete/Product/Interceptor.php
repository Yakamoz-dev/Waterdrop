<?php
namespace MageWorx\SearchSuiteAutocomplete\Block\Autocomplete\Product;

/**
 * Interceptor class for @see \MageWorx\SearchSuiteAutocomplete\Block\Autocomplete\Product
 */
class Interceptor extends \MageWorx\SearchSuiteAutocomplete\Block\Autocomplete\Product implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Helper\Product $catalogHelperProduct, \Magento\Framework\Stdlib\StringUtils $string, \Magento\Framework\Url\Helper\Data $urlHelper, \Magento\Framework\Data\Form\FormKey $formKey, \Magento\Catalog\Block\Product\Context $context, array $data = [])
    {
        $this->___init();
        parent::__construct($catalogHelperProduct, $string, $urlHelper, $formKey, $context, $data);
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
