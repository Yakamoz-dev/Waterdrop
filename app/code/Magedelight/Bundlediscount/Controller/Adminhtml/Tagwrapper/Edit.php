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
namespace Magedelight\Bundlediscount\Controller\Adminhtml\Tagwrapper;

use Magedelight\Bundlediscount\Model\TagwrapperFactory;

class Edit extends \Magedelight\Bundlediscount\Controller\Adminhtml\Tagwrapper
{
    /**
     * Edit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param TagwrapperFactory $tagwrapperFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magedelight\Bundlediscount\Model\TagwrapperFactory $tagwrapperFactory
    ) {
        $this->tagwrapperFactory = $tagwrapperFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }

    /**
     * Edit Newsletter Tagwrapper
     *
     * @return void
     */
    public function execute()
    {
        $tagWrapper = $this->tagwrapperFactory->create();
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $tagWrapper->load($id);
            if (!$tagWrapper->getId()) {
                $this->messageManager->addError(__('This Tag no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $values = $this->_getSession()->getData('gridmanager_template_form_data', true);
        if ($values) {
            $tagWrapper->addData($values);
        }

        $resultPage = $this->_initAction();

        $resultPage->addBreadcrumb(
            $id ? __('Edit Tag') : __('New Tag'),
            $id ? __('Edit Tag') : __('New Tag')
        );

        $resultPage->getConfig()->getTitle()->prepend(__('Tag'));
        $resultPage->getConfig()->getTitle()
            ->prepend($tagWrapper->getId() ? __('Update Tag')." ".
                $tagWrapper->getReleasenotesId() : __('New Tag'));

        return $resultPage;
    }
}
