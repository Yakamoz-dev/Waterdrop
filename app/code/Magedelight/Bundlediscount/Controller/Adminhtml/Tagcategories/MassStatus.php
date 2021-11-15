<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magedelight\Bundlediscount\Controller\Adminhtml\Tagcategories;

use Magento\Backend\App\Action;
use Magedelight\Bundlediscount\Controller\Adminhtml\Tagcategories;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory;

class MassStatus extends Tagcategories
{
    /**
     * MassActions filter
     *
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param Action\Context $context
     * @param Builder $productBuilder
     * @param \Magedelight\Bundlediscount\Model\Indexer\Product\Price\Processor $productPriceIndexerProcessor
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magedelight\Bundlediscount\Model\TagcategoriesFactory $tagcategoriesFactory,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->_redirect = $resultRedirectFactory;
        $this->tagcategoriesFactory = $tagcategoriesFactory;
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
        $tagCategoryIds = $collection->getAllIds();
        $status = (int) $this->getRequest()->getParam('status');
        try {
            $tagCategories = $this->tagcategoriesFactory->create();
            foreach ($tagCategoryIds as $id) {
                $tagCategories->load($id);
                $tagCategories->setIsActive($status);
                $tagCategories->save();
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $exceptionMessage = _('Something went wrong while updating the Tagcategory(ies) status.');
            $this->_getSession()->addException($e, $exceptionMessage);
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
