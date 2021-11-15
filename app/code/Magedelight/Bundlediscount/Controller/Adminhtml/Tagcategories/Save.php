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

use Magento\Framework\App\GiftwrapperTypesInterface;
use Magento\Framework\Exception\LocalizedException;
use \Magedelight\Bundlediscount\Controller\Adminhtml\Tagcategories;

class Save extends Tagcategories
{

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magedelight\Bundlediscount\Model\TagcategoriesFactory $tagcategoriesFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magedelight\Bundlediscount\Model\TagcategoriesFactory $tagcategoriesFactory
    ) {
        $this->tagcategoriesFactory = $tagcategoriesFactory;
        parent::__construct($context);
    }

    /**
     * Save Bundlediscount
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->getResponse()->setRedirect($this->getUrl('*/tagcategories'));
        }
   
        if ($data) {
            $tagCategory = $this->tagcategoriesFactory->create();
            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                $tagCategory->load($id);
            }
            
            try {
                $data = $request->getParams();
                $tagCategory->setData($data);
                $tagCategory->save();

                $this->messageManager->addSuccess(__('The Tag categories has been saved.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $tagCategory->getEntityId(),
                    '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError(nl2br($e->getMessage()));
                $this->_getSession()->setData('gridmanager_template_form_data', $this->getRequest()->getParams());
                return $resultRedirect->setPath('*/*/edit', ['id' => $tagCategory->getEntityId(),
                    '_current' => true]);
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving this template.'));
                $this->_getSession()->setData('gridmanager_template_form_data', $this->getRequest()->getParams());
                return $resultRedirect->setPath('*/*/edit', ['id' => $tagCategory->getEntityId(),
                    '_current' => true]);
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
