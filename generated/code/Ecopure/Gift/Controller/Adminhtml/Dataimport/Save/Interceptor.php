<?php
namespace Ecopure\Gift\Controller\Adminhtml\Dataimport\Save;

/**
 * Interceptor class for @see \Ecopure\Gift\Controller\Adminhtml\Dataimport\Save
 */
class Interceptor extends \Ecopure\Gift\Controller\Adminhtml\Dataimport\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Filesystem $fileSystem, \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory, \Magento\Framework\App\RequestInterface $request, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Image\AdapterFactory $adapterFactory, \Ecopure\Gift\Logger\Logger $logger)
    {
        $this->___init();
        parent::__construct($context, $fileSystem, $uploaderFactory, $request, $scopeConfig, $adapterFactory, $logger);
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
