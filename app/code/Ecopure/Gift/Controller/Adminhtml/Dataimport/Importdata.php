<?php

namespace Ecopure\Gift\Controller\Adminhtml\Dataimport;

use Magento\Framework\Controller\ResultFactory;

class Importdata extends \Magento\Backend\App\Action
{
    private $coreRegistry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {

        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
    }

    public function execute()
    {
        $rowData = $this->_objectManager->create('Ecopure\Gift\Model\Product');
        $this->coreRegistry->register('row_data', $rowData);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Import Product Data'));
        return $resultPage;
    }

    // used for acl.xml
    protected function _isAllowed()
    {
//        return $this->_authorization->isAllowed('Ecopure_Gift::add_datalocation');
        return true;
    }
}
