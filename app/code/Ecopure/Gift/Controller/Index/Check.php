<?php
namespace Ecopure\Gift\Controller\Index;
use Magento\Framework\App\Action\Context;
class Check extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;

    public function __construct(Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory){
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute() {
        $resultPage = $this->_resultPageFactory->create();

        // Add page title
        $resultPage->getConfig()->getTitle()->set(__('Water Filter Gift'));

        // Add breadcrumb
        /** @var \Magento\Theme\Block\Html\Breadcrumbs */
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home', [
                'label' => __('Home'),
                'title' => __('Home'),
                'link' => $this->_url->getUrl('')
            ]
        );
        $breadcrumbs->addCrumb('gift', [
                'label' => __('Free Gift'),
                'title' => __('Free Gift')
            ]
        );

        return $resultPage;
    }
}
