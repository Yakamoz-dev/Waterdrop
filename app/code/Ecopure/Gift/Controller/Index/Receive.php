<?php
namespace Ecopure\Gift\Controller\Index;
use Magento\Framework\App\Action\Context;

class Receive extends \Magento\Framework\App\Action\Action
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
        $newcus = $this->_session->getRoCustomer();
        if (!$newcus || !$newcus['id']) {
            $this->messageManager->addError(__('Sorry, Customer not found.'));
            $this->_redirect('gift/index/gift');
            return;
        }

        $ro_id = $newcus['ro_id'];

        $post = $this->getRequest()->getPostValue();
        $post['customer_id'] = $newcus['id'];
        $post['email'] = $newcus['email'];

        if (empty($post['product_info']) || $post['product_info'] == 0) {
            $this->messageManager->addError('Product not found');
            $this->_redirect('gift/index/gift');
            return;
        }

        try {
            if ($post['product_name']!='Warranty') {
                $data = [];
                $data['first_name'] = $post['first_name'];
                $data['last_name'] = $post['last_name'];
                $data['telephone'] = $post['telephone'];
                $data['email'] = $post['email'];
                $data['city'] = $post['city'];
                $data['customer_id'] = $post['customer_id'];
                $data['zip'] = $post['zip'];
                $data['state'] = $post['state'];
                $data['country'] = $post['country'];
                $data['address1'] = $post['address1'];
                $data['address2'] = $post['address2'];

                $newAddress = $this->_objectManager->create('Ecopure\Gift\Model\Address');
                $newAddress->setData($data);
                $newAddress->save();
            }

            $ord = array();
            $ord['product_id'] = $post['product_info'];
            $ord['customer_id'] = $post['customer_id'];
            $ord['options'] = (!empty($post['brands']) ? "Brand: " . $post['brands'] : "Brand: ".$post['product_name']) .' '. (!empty($post['models']) ? "Model:" . $post['models'] : "Model:".$post['product_name']);

            $neworder = $this->_objectManager->create('Ecopure\Gift\Model\Order');
            $neworder->setData($ord);
            $neworder->save();

            $product = $this->_objectManager->create('Ecopure\Gift\Model\Product');
            $product->load($ro_id,'ro_id');
            $product->setRoUsed(1);
            $product->save();

            $customer = $this->_objectManager->create('Ecopure\Gift\Model\Customer');
            $customer->load($newcus['id'],'id');
            $customer->setProductId($post['product_info']);
            $customer->save();

            $this->_session->setRoStep(2);
            $this->_session->setRoProduct($post['product_name']);
//            $this->_redirect('gift/index/comment');
            $this->_redirect('gift/index/success');
            return;
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while saving the Ro customer.')
            );
        }
    }
}
