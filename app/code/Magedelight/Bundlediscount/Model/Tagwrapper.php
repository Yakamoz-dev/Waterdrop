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
namespace Magedelight\Bundlediscount\Model;

class Tagwrapper extends \Magento\Framework\Model\AbstractModel
{
    /**
    * CMS page cache tag
    */
    const CACHE_TAG = 'gridmanager_template';

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory
     */
    protected $tagcategoriesCollectionFactory;

    /**
     * Tagwrapper constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\Tagwrapper\CollectionFactory $tagwrapperCollectionFactory
     * @param ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory $tagwrapperCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->tagwrapperCollectionFactory = $tagwrapperCollectionFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper');
    }
    
    /**
     * Prepare post's statuses.
     * Available event blog_post_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * @param $wraperId
     * @return array
     */
    public function getCategoriesBytag($wraperId)
    {
        $gccollection = $this->tagwrapperCollectionFactory->create();
        $gccollection->addFieldToFilter('id', ['eq' => $wraperId]);
        $output[] = '';
        foreach ($gccollection as $tagcategory) {
            $output =  $tagcategory->getCategory();
        }
        return $output;
    }
}
