<?php
namespace Ecopure\Gift\Controller\Index;
use Magento\Framework\App\Action\Context;

class Refer extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_objectManager;
    protected $_session;
    protected $logger;

    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ecopure\Gift\Model\Session $session,
        \Psr\Log\LoggerInterface $logger
    ){
        $this->_resultPageFactory = $resultPageFactory;
        $this->_objectManager = $objectManager;
        $this->_session = $session;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function execute() {
        $post = $this->getRequest()->getPostValue();
        $customer = $this->_session->getRoCustomer();

        if(isset($post)) {
            try {
                $data = [];
                $data['id'] = $post['customer_id'];
                $data['rating_comment'] = $post['comment'];
                $data['rating_star'] = $post['star'];
                $data['device'] = $post['device'];
                $data['channel'] = $post['channel'];
                $newCustomer = $this->_objectManager->create('Ecopure\Gift\Model\Customer');
                $newCustomer->setData($data);
                $success = $newCustomer->save();
                $result = array("customerId"=> $data['id'],"success"=>$success);
                echo json_encode($result);
            } catch (\Exception $e) {
                $this->logger->critical($e);
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while saving the Ro customer.')
                );
            }
        }
        return;
    }
}
