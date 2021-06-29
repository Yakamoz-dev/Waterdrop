<?php
namespace Ecopure\Gift\Controller\Adminhtml\Customer;
use Ecopure\Gift\Model\Customer as Customer;
use Magento\Backend\App\Action;

class Delete extends Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!($customer = $this->_objectManager->create(Customer::class)->load($id))) {
            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }
        try{
            $customer->delete();
            $this->messageManager->addSuccess(__('This customer has been deleted !'));
        } catch (Exception $e) {
            $this->messageManager->addError(__('Error while trying to delete customer: '));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
