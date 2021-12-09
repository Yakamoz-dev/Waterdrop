<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magedelight\Bundlediscount\Controller\Adminhtml\Tagwrapper;

use Magento\Backend\App\Action;
use Magedelight\Bundlediscount\Controller\Adminhtml\Tagwrapper;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory;

class MassStatus extends Tagwrapper
{
    /**
     * MassActions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var Redirect
     */
    protected $redirect;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Action\Context $context
     * @param Builder $productBuilder
     * @param \Magedelight\Tagwrapper\Model\Indexer\Product\Price\Processor $productPriceIndexerProcessor
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magedelight\Bundlediscount\Model\TagwrapperFactory $tagwrapperFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->tagwrapperFactory = $tagwrapperFactory;
        $this->filter = $filter;
        $this->_redirect = $resultRedirectFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Update product(s) status action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        $tagwrapperIds = $collection->getAllIds();
        $status = (int) $this->getRequest()->getParam('status');
        if ($status==1) {
            $statusMsg = "Enable";
        } else {
            $statusMsg = "Disable";
        }
        try {
            $tagWarpper = $this->tagwrapperFactory->create();
            foreach ($tagwrapperIds as $id) {
                $tagWarpper->load($id);
                $tagWarpper->setIsActive($status);
                $tagWarpper->save();
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->_getSession()->addException($e, __('Something went wrong while updating the tag(s) status.'));
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been '.$statusMsg.'.', $collectionSize));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
