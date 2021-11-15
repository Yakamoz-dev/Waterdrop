<?php
/**
 * Magedelight
 * Copyright (C) 2017 Magedelight <info@magedelight.com>
 *
 * @category Magedelight
 * @package Magedelight_Bundlediscount
 * @copyright Copyright (c) 2017 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */
namespace Magedelight\Bundlediscount\Controller\Adminhtml\Tagcategories;

use Magedelight\Bundlediscount\Controller\Adminhtml\Tagcategories;

class Edit extends Tagcategories
{
    private $tagCategoriesFactory;

    /**
     * Edit constructor.
     * @param \Magedelight\Bundlediscount\Model\TagcategoriesFactory $tagCategoriesFactory
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magedelight\Bundlediscount\Model\TagcategoriesFactory $tagCategoriesFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->tagCategoriesFactory = $tagCategoriesFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    private function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $tagCategories = $this->tagCategoriesFactory->create();
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $tagCategories->load($id);
            if (!$tagCategories->getEntityId()) {
                $this->messageManager->addError(__('This Tag category no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Tag Category') : __('New Tag Category'),
            $id ? __('Edit Tag Category') : __('New Tag Category')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Tag Categories'));
        $resultPage->getConfig()->getTitle()
            ->prepend($tagCategories->getId() ?
                __('Update Tag Category')." ".$tagCategories->getReleasenotesId() : __('New Tag Category'));
        return $resultPage;
    }
}
