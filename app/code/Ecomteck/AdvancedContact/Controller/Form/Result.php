<?php


namespace Ecomteck\AdvancedContact\Controller\Form;

use Magento\Framework\App\Action\Action;

class Result extends Action
{
    public function execute(){
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
