<?php
namespace Codazon\ThemeOptions\Controller\Adminhtml\Theme\Save;

/**
 * Interceptor class for @see \Codazon\ThemeOptions\Controller\Adminhtml\Theme\Save
 */
class Interceptor extends \Codazon\ThemeOptions\Controller\Adminhtml\Theme\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Codazon\ThemeOptions\Framework\App\ConfigFactory $themeConfigFactory, \Magento\Config\Model\ConfigFactory $configFactory, \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry)
    {
        $this->___init();
        parent::__construct($context, $themeConfigFactory, $configFactory, $indexerRegistry);
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
