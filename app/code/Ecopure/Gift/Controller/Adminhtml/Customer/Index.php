<?php
namespace Ecopure\Gift\Controller\Adminhtml\Customer;

use \Ecopure\Gift\Controller\Adminhtml\Customer as CustomerController;

class Index extends CustomerController
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Gift::customerro');
        $resultPage->getConfig()->getTitle()->prepend(__('Customers'));
        $resultPage->addBreadcrumb(__('Customers'), __('Customers'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return true;
    }
}
