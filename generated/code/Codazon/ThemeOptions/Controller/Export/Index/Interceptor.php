<?php
namespace Codazon\ThemeOptions\Controller\Export\Index;

/**
 * Interceptor class for @see \Codazon\ThemeOptions\Controller\Export\Index
 */
class Interceptor extends \Codazon\ThemeOptions\Controller\Export\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Cms\Model\ResourceModel\Page\Collection $pageCollection, \Magento\Cms\Model\ResourceModel\Block\Collection $blockCollection, \Magento\Cms\Model\Block $block, \Magento\Widget\Model\ResourceModel\Widget\Instance\Collection $widgetCollection, \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory, \Codazon\ThemeOptions\Setup\Model\Page $pageSetup, \Codazon\ThemeOptions\Setup\Model\Block $blockSetup, \Codazon\ThemeOptions\Setup\Model\Widget $widgetSetup, \Codazon\ThemeOptions\Setup\Model\Slideshow $slideshowSetup, \Codazon\ThemeOptions\Setup\Model\Blog\Category $blogCategorySetup, \Codazon\ThemeOptions\Setup\Model\Blog\Post $blogPostSetup, \Codazon\ThemeOptions\Setup\Model\MegaMenu $megaMenuSetup, \Magento\Theme\Model\ResourceModel\Theme\CollectionFactory $themeCollectionFactory)
    {
        $this->___init();
        parent::__construct($context, $pageCollection, $blockCollection, $block, $widgetCollection, $widgetFactory, $pageSetup, $blockSetup, $widgetSetup, $slideshowSetup, $blogCategorySetup, $blogPostSetup, $megaMenuSetup, $themeCollectionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'execute');
        return $pluginInfo ? $this->___callPlugins('execute', func_get_args(), $pluginInfo) : parent::execute();
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        return $pluginInfo ? $this->___callPlugins('dispatch', func_get_args(), $pluginInfo) : parent::dispatch($request);
    }
}
