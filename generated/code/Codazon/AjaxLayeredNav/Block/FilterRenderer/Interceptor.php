<?php
namespace Codazon\AjaxLayeredNav\Block\FilterRenderer;

/**
 * Interceptor class for @see \Codazon\AjaxLayeredNav\Block\FilterRenderer
 */
class Interceptor extends \Codazon\AjaxLayeredNav\Block\FilterRenderer implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, array $data = [])
    {
        $this->___init();
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function render(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'render');
        return $pluginInfo ? $this->___callPlugins('render', func_get_args(), $pluginInfo) : parent::render($filter);
    }
}
