<?php
/**
 * Magedelight
 * Copyright (C) 2016 Magedelight <info@magedelight.com>.
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @category Magedelight
 *
 * @copyright Copyright (c) 2016 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

/**
 * Customers defined options.
 */

namespace Magedelight\Bundlediscount\Block\Adminhtml\Catalog\Product\Edit\Tab\Options;

use Magento\Backend\Block\Widget;
use Magento\Catalog\Model\Product;

class Option extends Widget
{
    /**
     * @var string
     */
    protected $_template = 'catalog/product/edit/tab/bundles/option.phtml';

    /**
     * Customer Group factory.
     *
     * @var \Magento\Customer\Model\GroupFactory
     */
    private $customerGroupFactory;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    private $systemStore;

    /**
     * Option constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Customer\Model\GroupFactory $customerGroupFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param Product $product
     * @param \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount
     * @param \Magedelight\Bundlediscount\Model\Bundleitems $bundleitems
     * @param \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $tagCatagoryCollection
     * @param \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory $tagwrapperCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Model\GroupFactory $customerGroupFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Catalog\Model\Product $product,
        \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount,
        \Magedelight\Bundlediscount\Model\Bundleitems $bundleitems,
        \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $tagCatagoryCollection,
        \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory $tagwrapperCollection,
        array $data = []
    ) {
        $this->customerGroupFactory = $customerGroupFactory;
        $this->systemStore = $systemStore;
        $this->bundlediscount = $bundlediscount;
        $this->bundleItems = $bundleitems;
        $this->product = $product;
        $this->_tagCatagoryCollection = $tagCatagoryCollection;
        $this->_tagwrapperCollection = $tagwrapperCollection;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve options field name prefix.
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'bundlediscount_options';
    }

    /**
     * Retrieve options field id prefix.
     *
     * @return string
     */
    public function getFieldId()
    {
        return 'bundlediscount_option';
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->addChild(
            'add_selection_button',
            'Magento\Backend\Block\Widget\Button',
            ['label' => __('Add Product'), 'class' => 'add', 'id' => $this->getFieldId().'_{{index}}_add_button']
        );

        $this->addChild(
            'close_search_button',
            'Magento\Backend\Block\Widget\Button',
            ['label' => __('Close'), 'class' => 'back no-display',
                'id' => $this->getFieldId().'_{{index}}_close_button']
        );

        $this->addChild(
            'option_delete_button',
            'Magento\Backend\Block\Widget\Button',
            ['label' => __('Delete Bundle'), 'class' => 'delete delete-product-option']
        );
        $this->addChild(
            'md_bundlediscount_selection_template',
            'Magedelight\Bundlediscount\Block\Adminhtml\Catalog\Product\Edit\Tab\Options\Selection'
        );

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    /**
     * @return string
     */
    public function getCloseSearchButtonHtml()
    {
        return $this->getChildHtml('close_search_button');
    }

    /**
     * @return string
     */
    public function getAddSelectionButtonHtml()
    {
        return $this->getChildHtml('add_selection_button');
    }

    /**
     * @return string
     */
    public function getOptionDeleteButtonHtml()
    {
        return $this->getChildHtml('option_delete_button');
    }

    /**
     * @return string
     */
    public function getSelectionHtml()
    {
        return $this->getChildHtml('md_bundlediscount_selection_template');
    }

    /**
     * @return array
     */
    public function getBundles()
    {
        $data = [];
        $productId = $this->getRequest()->getParam('id');

        $bundles = $this->bundlediscount->getCollection()
                ->addFieldToSelect('*')->addFieldToFilter('product_id', ['eq' => $productId])
                ->setOrder('sort_order', 'ASC');

        foreach ($bundles as $bundle) {
            $bundleId = $bundle->getId();
            $items = $this->bundleItems->getCollection()
                        ->addFieldToFilter('bundle_id', ['eq' => $bundleId])
                        ->setOrder('sort_order', 'ASC');

            foreach ($items as $item) {
                $productId = $item->getProductId();
                $product = $this->product->load($productId);
                $items->getItemByColumnValue('item_id', $item->getId())
                        ->setName($product->getName())
                        ->setSku($product->getSku());
            }
            $bundle->setData('selections', $items);
            $data[$bundleId] = $bundle;
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getStoreViewOptions()
    {
        $storeOptions = $this->systemStore->getStoreValuesForForm();
        $optionString = '';
        foreach ($storeOptions as $options) {
            if (!is_array($options['value'])) {
                $optionString .= '<option value="'.$options['value'].'">'.$options['label'].'   </option>';
            } else {
                $optionString .= '<optgroup label="'.$options['label'].'">';
                foreach ($options['value'] as $suboptions) {
                    $optionString .= '<option value="'.$suboptions['value'].'">'.$suboptions['label'].'</option>';
                }
                $optionString .= '</optgroup>';
            }
        }

        return $optionString;
    }

    /**
     * @return string
     */
    public function getCustomerGroupsOptions()
    {
        $groupCollection = $this->customerGroupFactory->create()->getCollection()
            ->load()
            ->toOptionHash();
        $optionString = '';
        foreach ($groupCollection as $groupId => $code) {
            $optionString .= '<option value="'.$groupId.'">'.$code.'</option>';
        }

        return $optionString;
    }

    /**
     * @return string
     */
    public function getTagOptions()
    {
        $categoriesCollection = $this->_tagCatagoryCollection->create()
                            ->addFieldToFilter('is_active', 1);
        $optionString = '';
        foreach ($categoriesCollection as $categories) {
            $optionString .= '<optgroup label="'.$categories->getName().'">';
            $tagCollection = $this->_tagwrapperCollection->create()->addFieldToFilter(
                'category',
                $categories->getEntityId()
            )->addFieldToFilter('is_active', 1);
            foreach ($tagCollection as $tag) {
                $optionString .= '<option value="'.$tag->getId().'">'.$tag->getName().'</option>';
            }
            $optionString .= '</optgroup>';
        }
        return $optionString;
    }
}
