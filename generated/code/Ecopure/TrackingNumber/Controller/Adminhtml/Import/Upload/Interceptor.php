<?php
namespace Ecopure\TrackingNumber\Controller\Adminhtml\Import\Upload;

/**
 * Interceptor class for @see \Ecopure\TrackingNumber\Controller\Adminhtml\Import\Upload
 */
class Interceptor extends \Ecopure\TrackingNumber\Controller\Adminhtml\Import\Upload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\File\Csv $csvProcessor)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $uploaderFactory, $filesystem, $csvProcessor);
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
