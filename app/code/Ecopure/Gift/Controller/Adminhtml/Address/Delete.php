<?php
namespace Ecopure\Gift\Controller\Adminhtml\Address;
use Ecopure\Gift\Model\Address as Address;
use Magento\Backend\App\Action;

class Delete extends Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('address_id');

        if (!($address = $this->_objectManager->create(Address::class)->load($id))) {
            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }
        try{
            $address->delete();
            $this->messageManager->addSuccess(__('This address has been deleted !'));
        } catch (Exception $e) {
            $this->messageManager->addError(__('Error while trying to delete address: '));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
