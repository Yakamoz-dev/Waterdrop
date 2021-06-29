<?php
namespace Codazon\Lookbookpro\Controller\Adminhtml\Lookbook\ImageUpload;

/**
 * Interceptor class for @see \Codazon\Lookbookpro\Controller\Adminhtml\Lookbook\ImageUpload
 */
class Interceptor extends \Codazon\Lookbookpro\Controller\Adminhtml\Lookbook\ImageUpload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Codazon\Lookbookpro\Model\ImageUploader $imageUploader, \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory, \Magento\Framework\Filesystem $filesystem, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase, \Psr\Log\LoggerInterface $logger)
    {
        $this->___init();
        parent::__construct($context, $imageUploader, $uploaderFactory, $filesystem, $storeManager, $coreFileStorageDatabase, $logger);
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
