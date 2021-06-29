<?php
namespace Prymag\ReviewsImporter\Controller\Adminhtml\Import\Upload;

/**
 * Interceptor class for @see \Prymag\ReviewsImporter\Controller\Adminhtml\Import\Upload
 */
class Interceptor extends \Prymag\ReviewsImporter\Controller\Adminhtml\Import\Upload implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\File\Csv $csvProcessor, \Magento\Store\Model\StoreManager $storeManager, \Magento\Review\Model\ReviewFactory $reviewFactory, \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory, \Magento\Review\Model\RatingFactory $ratingFactory, \Magento\Customer\Model\CustomerFactory $customerFactory)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $uploaderFactory, $filesystem, $csvProcessor, $storeManager, $reviewFactory, $reviewCollectionFactory, $ratingFactory, $customerFactory);
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
