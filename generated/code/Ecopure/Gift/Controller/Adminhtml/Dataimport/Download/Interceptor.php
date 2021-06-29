<?php
namespace Ecopure\Gift\Controller\Adminhtml\Dataimport\Download;

/**
 * Interceptor class for @see \Ecopure\Gift\Controller\Adminhtml\Dataimport\Download
 */
class Interceptor extends \Ecopure\Gift\Controller\Adminhtml\Dataimport\Download implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Backend\App\Response\Http\FileFactory $fileFactory, \Magento\Framework\Filesystem\DirectoryList $directory)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $fileFactory, $directory);
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
