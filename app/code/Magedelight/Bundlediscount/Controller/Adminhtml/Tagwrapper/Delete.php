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

use Magento\Backend\App\Action;

class Delete extends \Magedelight\Bundlediscount\Controller\Adminhtml\Tagwrapper
{
    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param \Magedelight\Bundlediscount\Model\TagwrapperFactory $tagwrapperFactory
     */
    public function __construct(
        Action\Context $context,
        \Magedelight\Bundlediscount\Model\TagwrapperFactory $tagwrapperFactory
    ) {
        $this->tagwrapperFactory = $tagwrapperFactory;
        parent::__construct($context);
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $tagWrapper = $this->tagwrapperFactory->create();
                $tagWrapper->load($id);
                $tagWrapper->delete();
                $this->messageManager->addSuccess(__('The Tag has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a Tag to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
