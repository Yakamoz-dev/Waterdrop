<?php
namespace Ecopure\Gift\Controller\Adminhtml\Address;

use \Ecopure\Gift\Controller\Adminhtml\Address as AddressController;

class Index extends AddressController
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Gift::address');
        $resultPage->getConfig()->getTitle()->prepend(__('Addresses'));
        $resultPage->addBreadcrumb(__('Addresses'), __('Addresses'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return true;
    }
}
