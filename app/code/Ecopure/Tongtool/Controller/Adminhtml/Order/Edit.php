<?php
/**
 * Ecopure_Gift extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Ecopure
 * @package   Ecopure_Gift
 * @copyright 2016 Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru
 */
namespace Ecopure\Tongtool\Controller\Adminhtml\Order;

use Ecopure\Tongtool\Controller\Adminhtml\Order;

class Edit extends Order
{
    /**
     * Initialize current author and set it in the registry.
     *
     * @return int
     */
    protected function _initOrder()
    {
        $id = $this->getRequest()->getParam('id');
        $this->coreRegistry->register("tongtool_order_id", $id);

        return $id;
    }

    /**
     * Edit or create author
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = $this->_initOrder();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Gift::order');
        $resultPage->getConfig()->getTitle()->prepend(__('Order'));
        $resultPage->addBreadcrumb(__('Order'), __('Order'), $this->getUrl('ecopure_tongtool/order'));

        if ($id === null) {
            $resultPage->addBreadcrumb(__('New Order'), __('New Order'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Order'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Order'), __('Edit Order'));
            $resultPage->getConfig()->getTitle()->prepend('Order: '.$id);
        }
        return $resultPage;
    }
}
