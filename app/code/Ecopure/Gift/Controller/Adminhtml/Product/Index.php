<?php
namespace Ecopure\Gift\Controller\Adminhtml\Product;

use \Ecopure\Gift\Controller\Adminhtml\Product as ProductController;

class Index extends ProductController
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Gift::product');
        $resultPage->getConfig()->getTitle()->prepend(__('Products'));
        $resultPage->addBreadcrumb(__('Products'), __('Products'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return true;
    }
}
