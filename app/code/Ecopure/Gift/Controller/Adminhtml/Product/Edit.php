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
namespace Ecopure\Gift\Controller\Adminhtml\Product;

use Ecopure\Gift\Controller\Adminhtml\Product;
use Ecopure\Gift\Controller\RegistryConstants;

class Edit extends Product
{
    /**
     * Initialize current author and set it in the registry.
     *
     * @return int
     */
    protected function _initProduct()
    {
        $productId = $this->getRequest()->getParam('ro_id');
        $this->coreRegistry->register(RegistryConstants::CURRENT_PRODUCT_ID, $productId);

        return $productId;
    }

    /**
     * Edit or create author
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $productId = $this->_initProduct();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Gift::product');
        $resultPage->getConfig()->getTitle()->prepend(__('Product'));
        $resultPage->addBreadcrumb(__('Products'), __('Products'), $this->getUrl('ecopure_gift/product'));

        if ($productId === null) {
            $resultPage->addBreadcrumb(__('New Product'), __('New Product'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Product'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Product'), __('Edit Product'));
            $resultPage->getConfig()->getTitle()->prepend('Product: '.$productId);
        }
        return $resultPage;
    }
}
