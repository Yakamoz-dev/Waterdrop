<?php
namespace Ecopure\Gift\Controller\Adminhtml\Gift;
use Ecopure\Gift\Model\Gift as Gift;
use Magento\Backend\App\Action;

class Delete extends Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if (!($order = $this->_objectManager->create(Gift::class)->load($id))) {
            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }
        try{
            $order->delete();
            $this->messageManager->addSuccess(__('This order has been deleted !'));
        } catch (Exception $e) {
            $this->messageManager->addError(__('Error while trying to delete gift: '));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
