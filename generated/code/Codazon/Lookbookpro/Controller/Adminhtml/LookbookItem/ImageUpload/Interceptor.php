<?php
namespace Codazon\Lookbookpro\Controller\Adminhtml\LookbookItem\ImageUpload;

/**
 * Interceptor class for @see \Codazon\Lookbookpro\Controller\Adminhtml\LookbookItem\ImageUpload
 */
class Interceptor extends \Codazon\Lookbookpro\Controller\Adminhtml\LookbookItem\ImageUpload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Codazon\Lookbookpro\Helper\Media $swatchHelper, \Magento\Framework\Image\AdapterFactory $adapterFactory, \Magento\Catalog\Model\Product\Media\Config $config, \Magento\Framework\Filesystem $filesystem, \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory)
    {
        $this->___init();
        parent::__construct($context, $swatchHelper, $adapterFactory, $config, $filesystem, $uploaderFactory);
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
