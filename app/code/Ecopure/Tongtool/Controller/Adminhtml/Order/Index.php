<?php
namespace Ecopure\Tongtool\Controller\Adminhtml\Order;

use \Ecopure\Tongtool\Controller\Adminhtml\Order as OrderController;

class Index extends OrderController
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Tongtool::order');
        $resultPage->getConfig()->getTitle()->prepend(__('Order'));
        $resultPage->addBreadcrumb(__('Order'), __('Order'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return true;
    }
}
