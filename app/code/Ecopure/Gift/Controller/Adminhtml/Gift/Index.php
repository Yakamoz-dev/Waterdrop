<?php
namespace Ecopure\Gift\Controller\Adminhtml\Gift;

use \Ecopure\Gift\Controller\Adminhtml\Gift as GiftController;

class Index extends GiftController
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Gift::giftro');
        $resultPage->getConfig()->getTitle()->prepend(__('Gifts'));
        $resultPage->addBreadcrumb(__('Gifts'), __('Gifts'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return true;
    }
}
