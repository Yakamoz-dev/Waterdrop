<?php
namespace Ecopure\Gift\Controller\Index;
use Magento\Framework\App\Action\Context;
class Success extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_session;

    public function __construct(Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Ecopure\Gift\Model\Session $session){
        $this->_resultPageFactory = $resultPageFactory;
        $this->_session = $session;
        parent::__construct($context);
    }

    public function execute() {
        $resultPage = $this->_resultPageFactory->create();
        $this->_session->setRoCustomer(null);
        $this->_session->setRoStep(null);
        $this->_session->setRoProduct(null);
        $this->_session->setRoCheck(null);

        // Add page title
        $resultPage->getConfig()->getTitle()->set(__('Success'));

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
                'label' => __('Success'),
                'title' => __('Success')
            ]
        );

        return $resultPage;
    }
}
