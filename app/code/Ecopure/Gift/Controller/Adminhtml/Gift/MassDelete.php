<?php
namespace Ecopure\Gift\Controller\Adminhtml\Gift;
use Magento\Backend\App\Action;
use Ecopure\Gift\Model\Gift;

class MassDelete extends Action
{
    public function execute()
    {
        $ids = $this->getRequest()->getParam('selected', []);
        if (!is_array($ids) || !count($ids)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index', array('_current' => true));
        }
        foreach ($ids as $id) {
            if ($contact = $this->_objectManager->create(Gift::class)->load($id)) {
                $contact->delete();
            }
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', count($ids)));

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index', array('_current' => true));
    }
}
