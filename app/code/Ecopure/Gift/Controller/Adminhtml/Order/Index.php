<?php
namespace Ecopure\Gift\Controller\Adminhtml\Order;

use \Ecopure\Gift\Controller\Adminhtml\Order as OrderController;

class Index extends OrderController
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Gift::order');
        $resultPage->getConfig()->getTitle()->prepend(__('Orders'));
        $resultPage->addBreadcrumb(__('Orders'), __('Orders'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return true;
    }
}
