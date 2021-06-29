<?php
namespace Ecopure\Gift\Controller\Adminhtml\Product;
use Ecopure\Gift\Model\Product as Product;
use Magento\Backend\App\Action;

class Delete extends Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('ro_id');

        if (!($product = $this->_objectManager->create(Product::class)->load($id))) {
            $this->messageManager->addError(__('Unable to proceed. Please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }
        try{
            $product->delete();
            $this->messageManager->addSuccess(__('This product has been deleted !'));
        } catch (Exception $e) {
            $this->messageManager->addError(__('Error while trying to delete product: '));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
