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
namespace Ecopure\Gift\Controller\Adminhtml\Address;

use Ecopure\Gift\Controller\Adminhtml\Address;
use Ecopure\Gift\Controller\RegistryConstants;

class Edit extends Address
{
    /**
     * Initialize current author and set it in the registry.
     *
     * @return int
     */
    protected function _initAddress()
    {
        $addressId = $this->getRequest()->getParam('address_id');
        $this->coreRegistry->register(RegistryConstants::CURRENT_ADDRESS_ID, $addressId);

        return $addressId;
    }

    /**
     * Edit or create author
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $addressId = $this->_initAddress();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Gift::address');
        $resultPage->getConfig()->getTitle()->prepend(__('Address'));
        $resultPage->addBreadcrumb(__('Addresses'), __('Addresses'), $this->getUrl('ecopure_gift/address'));

        if ($addressId === null) {
            $resultPage->addBreadcrumb(__('New Address'), __('New Address'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Address'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Address'), __('Edit Address'));
            $resultPage->getConfig()->getTitle()->prepend('Address: '.$addressId);
        }
        return $resultPage;
    }
}
