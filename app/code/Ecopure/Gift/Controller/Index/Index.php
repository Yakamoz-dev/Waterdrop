<?php
namespace Ecopure\Gift\Controller\Index;
use Magento\Framework\App\Action\Context;
class Index extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;

    public function __construct(Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory){
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute() {
        $resultPage = $this->_resultPageFactory->create();

        // Add page title
        $resultPage->getConfig()->getTitle()->set(__('Warranty - Protect your every order'));
        $resultPage->getConfig()->setDescription(__('We offer 30-day money back guarantee,a 1-year manufacturer warranty, and lifetime tech support for all our products! Register and enjoy the warranty service!'));

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
                'label' => __('Warranty'),
                'title' => __('Warranty')
            ]
        );

        return $resultPage;
    }
}
