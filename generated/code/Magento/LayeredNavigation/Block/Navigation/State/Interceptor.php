<?php
namespace Magento\LayeredNavigation\Block\Navigation\State;

/**
 * Interceptor class for @see \Magento\LayeredNavigation\Block\Navigation\State
 */
class Interceptor extends \Magento\LayeredNavigation\Block\Navigation\State implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Catalog\Model\Layer\Resolver $layerResolver, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $layerResolver, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveFilters()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getActiveFilters');
        return $pluginInfo ? $this->___callPlugins('getActiveFilters', func_get_args(), $pluginInfo) : parent::getActiveFilters();
    }
}
