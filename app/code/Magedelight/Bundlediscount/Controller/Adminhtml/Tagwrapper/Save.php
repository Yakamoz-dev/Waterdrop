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
use Magento\Framework\App\GiftwrapperTypesInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magedelight\Bundlediscount\Controller\Adminhtml\Tagwrapper
{
    /**
     * Save constructor.
     * @param TagwrapperFactory $tagwrapperFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magedelight\Bundlediscount\Model\TagwrapperFactory $tagwrapperFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->tagWrapperFactory = $tagwrapperFactory;
        parent::__construct($context);
    }

    /**
     * Save Newsletter Bundlediscount
     *
     * @return void
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->getResponse()->setRedirect($this->getUrl('*/tagwrapper'));
        }
   
        $tagWrapper = $this->tagWrapperFactory->create();
        $id = $this->getRequest()->getPostValue('id');
        if ($id) {
            $tagWrapper->load($id);
        }
        if ($data) {
            try {
                $data = $request->getParams();
                $tagWrapper->setData($data);
                $tagWrapper->save();

                $this->messageManager->addSuccess(__('The Tag Wrapper has been saved.'));
                $this->_getSession()->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $tagWrapper->getId(),
                        '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError(nl2br($e->getMessage()));
                $this->_getSession()->setData('gridmanager_template_form_data', $this->getRequest()->getParams());
                return $resultRedirect->setPath('*/*/edit', ['id' => $tagWrapper->getGridpart2templateId(),
                    '_current' => true]);
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving this template.'));
                $this->_getSession()->setData('gridmanager_template_form_data', $this->getRequest()->getParams());
                return $resultRedirect->setPath('*/*/edit', ['id' => $tagWrapper->getGridpart2templateId(),
                    '_current' => true]);
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
