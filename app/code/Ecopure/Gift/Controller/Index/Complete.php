<?php
namespace Ecopure\Gift\Controller\Index;
use Magento\Framework\App\Action\Context;

class Complete extends \Magento\Framework\App\Action\Action
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
        $this->_session->setRoCustomer(null);
        $this->_session->setRoStep(null);
        $this->_session->setRoProduct(null);
        $this->_session->setRoCheck(null);

        if(isset($post) && !empty($post)) {
            try {
                $proid = $post['product_id'];
                if ($proid) {
                    $product = $this->_objectManager->create('Ecopure\Gift\Model\Product');
                    $product->load($post['product_id'],'ro_productid');
                    $pro = $product->getData();

                    if (empty($pro)) {
                        $ordid = preg_replace('/-/i', '', $proid);
                        $product->load($ordid,'ro_order_no');
                        $pro = $product->getData();
                    }

                    if (!empty($pro)) {
                        if ($pro['ro_used'] == 0) {
                            $newCustomer = $this->_objectManager->create('Ecopure\Gift\Model\Customer');
                            $newCustomer->load($pro['ro_id'],'ro_id');
                            $cus = $newCustomer->getData();
                            if (!empty($cus)) {
                                $this->_session->setRoCustomer($cus);
                                $this->_session->setRoStep(1);
                            }
                            $this->_session->setRoCheck(1);
                            $success = 1;
                            $info = "";
                        } else {
                            $success = 0;
                            $info = '<span style="color:red;">Sorry, you have already applied for your free gift.</span>';
                        }
                    } else {
                        $success = 0;
                        $info = '<span style="color:red;">No result, please try again or contact service@water-filter.com</span>';
                    }
                } else {
                    $success = 0;
                    $info = '<span style="color:red;">Please Enter Your Product ID or Order Number.</span>';
                }
                $result = array("info" => $info,"success" => $success);
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
