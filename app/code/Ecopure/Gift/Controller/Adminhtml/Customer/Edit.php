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
namespace Ecopure\Gift\Controller\Adminhtml\Customer;

use Ecopure\Gift\Controller\Adminhtml\Customer;
use Ecopure\Gift\Controller\RegistryConstants;

class Edit extends Customer
{
    /**
     * Initialize current author and set it in the registry.
     *
     * @return int
     */
    protected function _initCustomer()
    {
        $customerId = $this->getRequest()->getParam('id');
        $this->coreRegistry->register(RegistryConstants::CURRENT_CUSTOMER_ID, $customerId);

        return $customerId;
    }

    /**
     * Edit or create author
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $customerId = $this->_initCustomer();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Gift::customerro');
        $resultPage->getConfig()->getTitle()->prepend(__('Customer'));
        $resultPage->addBreadcrumb(__('Customers'), __('Customers'), $this->getUrl('ecopure_gift/customer'));

        if ($customerId === null) {
            $resultPage->addBreadcrumb(__('New Customer'), __('New Customer'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Customer'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Customer'), __('Edit Customer'));
            $resultPage->getConfig()->getTitle()->prepend('Customer: '.$customerId);
        }
        return $resultPage;
    }
}
