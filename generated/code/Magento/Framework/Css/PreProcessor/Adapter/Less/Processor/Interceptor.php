<?php
namespace Magento\Framework\Css\PreProcessor\Adapter\Less\Processor;

/**
 * Interceptor class for @see \Magento\Framework\Css\PreProcessor\Adapter\Less\Processor
 */
class Interceptor extends \Magento\Framework\Css\PreProcessor\Adapter\Less\Processor implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Psr\Log\LoggerInterface $logger, \Magento\Framework\App\State $appState, \Magento\Framework\View\Asset\Source $assetSource, \Magento\Framework\Css\PreProcessor\File\Temporary $temporaryFile)
    {
        $this->___init();
        parent::__construct($logger, $appState, $assetSource, $temporaryFile);
    }

    /**
     * {@inheritdoc}
     */
    public function processContent(\Magento\Framework\View\Asset\File $asset)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'processContent');
        return $pluginInfo ? $this->___callPlugins('processContent', func_get_args(), $pluginInfo) : parent::processContent($asset);
    }
}
