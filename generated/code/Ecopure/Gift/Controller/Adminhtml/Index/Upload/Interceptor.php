<?php
namespace Ecopure\Gift\Controller\Adminhtml\Index\Upload;

/**
 * Interceptor class for @see \Ecopure\Gift\Controller\Adminhtml\Index\Upload
 */
class Interceptor extends \Ecopure\Gift\Controller\Adminhtml\Index\Upload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Ecopure\Gift\Model\ImageUploader $imageUploader, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\Filesystem\Io\File $fileIo, \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->___init();
        parent::__construct($context, $imageUploader, $filesystem, $fileIo, $storeManager);
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
