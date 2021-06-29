<?php
namespace Ecopure\Gift\Controller\Index;
use Magento\Framework\App\Action\Context;
class Gift extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_session;

    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Ecopure\Gift\Model\Session $session
    ){
        $this->_resultPageFactory = $resultPageFactory;
        $this->_session = $session;
        parent::__construct($context);
    }

    public function execute() {
        $step = $this->_session->getRoStep();
        if ($step && $step==2) {
            $this->messageManager->addError(__('You have selected a gift, please fill in the comment.'));
            $this->_redirect('gift/index/comment');
            return;
        }
        $newcus = $this->_session->getRoCustomer();
        if (!$newcus || !$newcus['id']) {
            $this->messageManager->addError(__('Sorry, you didn\'t register the warranty. Please register it first.'));
            $this->_redirect('gift');
            return;
        }

        $resultPage = $this->_resultPageFactory->create();

        // Add page title
        $resultPage->getConfig()->getTitle()->set(__('Gift'));

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
                'label' => __('Gift'),
                'title' => __('Gift')
            ]
        );

        return $resultPage;
    }
}
