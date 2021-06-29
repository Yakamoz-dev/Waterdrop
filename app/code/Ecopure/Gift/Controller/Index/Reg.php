<?php
namespace Ecopure\Gift\Controller\Index;
use Magento\Framework\App\Action\Context;
use Ecopure\Gift\Model\Email;

class Reg extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_objectManager;
    protected $_session;
    protected $_email;
    protected $logger;

    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ecopure\Gift\Model\Session $session,
        Email $email,
        \Ecopure\Gift\Logger\Logger $logger
    ){
        $this->_resultPageFactory = $resultPageFactory;
        $this->_objectManager = $objectManager;
        $this->_session = $session;
        $this->_email = $email;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function execute() {
        $post = $this->getRequest()->getPostValue();
        /* get ro product */
        $product = $this->_objectManager->create('Ecopure\Gift\Model\Product');
        if (isset($post['ro_productid']) && !empty($post['ro_productid'])) {
            $product->load($post['ro_productid'],'ro_productid');
        } else {
            $order_id = preg_replace('/-/i','',$post['ro_order_no']);
            $product->load($order_id,'ro_order_no');
        }
        $pro = $product->getData();

        if (empty($pro)) {
            $this->messageManager->addError(__('No Corresponding Product ID or Order No.'));
            $this->_redirect('gift');
            return;
        }
        if ($pro['ro_model_no'] != $post['ro_model_no']) {
            $this->messageManager->addError(__('No Corresponding Model No.'));
            $this->_redirect('gift');
            return;
        }
        if ($pro['ro_used']) {
            $this->messageManager->addError(__('Sorry Your Product ID or Order No was used!'));
            $this->_redirect('gift');
            return;
        }

        /* get ro customer */
        $customer = $this->_objectManager->create('Ecopure\Gift\Model\Customer');
        $customer->load($pro['ro_id'],'ro_id');
        $cus = $customer->getData();
        if ($pro['ro_regd']) {
            if (!empty($cus) && $cus['id']) {
                $this->_session->setRoCustomer($cus);
            }
            $this->messageManager->addError(__('Registration has been completed, please choose a gift.'));
            $this->_redirect('gift/index/gift');
            return;
        }
        if (!empty($cus) && $cus['id']) {
            if ($cus['ro_id'] != $pro['ro_id']) {
                $this->messageManager->addError(__('Your account has benn registed!'));
                $this->_redirect('gift');
                return;
            }

            $this->_redirect('gift/index/gift');
            return;
        }

        $installationData = date("Y-m-d", strtotime(strval($post['installation_data'])));
        $purchaseDate = date("Y-m-d", strtotime(strval($post['purchase_date'])));

        if ($installationData < $purchaseDate) {
            $this->messageManager->addError(__('Installation data Must late Purchase date.'));
            $this->_redirect('gift');
            return;
        }

        try {
            $data = [];
            $data['first_name'] = $post['first_name'];
            $data['last_name'] = $post['last_name'];
            $data['email'] = $post['email'];
            $data['installation_data'] = $installationData;
            $data['purchase_date'] = $purchaseDate;
            $data['telephone'] = $post['telephone'];
            $data['order_number'] = $post['ro_order_no'];
            $data['product_id'] = $post['ro_productid'];
            $data['model_no'] = $post['ro_model_no'];
            $data['ro_id'] = $pro['ro_id'];
            $newCustomer = $this->_objectManager->create('Ecopure\Gift\Model\Customer');
            $newCustomer->setData($data);
            $newCustomer->save();

            $product->setRoRegd(1);
            $product->save();

            $this->_session->setRoCustomer($newCustomer);
            $this->_session->setRoStep(1);
            $this->_session->setRoCheck(null);
            $resultRedirect = $this->resultRedirectFactory->create();
            if ($pro['only_reg']) {
                $resultRedirect->setPath('gift/index/success');
            } else {
                $resultRedirect->setPath('gift/index/gift');
            }
            $this->_email->execute($post['first_name'],$post['email']);
            return $resultRedirect;
        } catch (\Exception $e){
            $this->logger->info($e->getMessage());
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while saving the Ro customer.')
            );
        }
    }
}
