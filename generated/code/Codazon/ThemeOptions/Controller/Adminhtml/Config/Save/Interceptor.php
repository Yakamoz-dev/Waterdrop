<?php
namespace Codazon\ThemeOptions\Controller\Adminhtml\Config\Save;

/**
 * Interceptor class for @see \Codazon\ThemeOptions\Controller\Adminhtml\Config\Save
 */
class Interceptor extends \Codazon\ThemeOptions\Controller\Adminhtml\Config\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Codazon\ThemeOptions\Model\Config\Structure $configStructure, \Codazon\ThemeOptions\Model\ConfigFactory $configFactory, \Magento\Framework\App\Cache\Manager $cache, \Magento\Framework\Stdlib\StringUtils $string, \Codazon\ThemeOptions\Framework\App\Config $themeConfig, \Magento\Framework\App\State $state, \Magento\Framework\Filesystem $fileSystem)
    {
        $this->___init();
        parent::__construct($context, $configStructure, $configFactory, $cache, $string, $themeConfig, $state, $fileSystem);
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
