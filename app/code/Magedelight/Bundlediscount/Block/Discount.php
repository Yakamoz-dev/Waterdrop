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

namespace Magedelight\Bundlediscount\Block;

use Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory as TagwrapperCollection;
use Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory as Tagcategories;

class Discount extends \Magento\Framework\View\Element\Template
{
    /**
     * Default toolbar block name.
     *
     * @var string
     */
    protected $_defaultToolbarBlock = 'Magedelight\Bundlediscount\Block\ProductList\Toolbar';
   
    /**
     * @var \Magento\Tax\Model\CalculationFactory
     */
    protected $_calculationFactory;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\TagwrapperFactory
     */
    public $tagwrapperFactory;

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\TagcategoriesFactory
     */
    public $tagcategoriesFactory;

    private $objectFactory;

    /**
     * Discount constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Tax\Model\CalculationFactory $calculationFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param Tagcategories $tagcategoriesFactory
     * @param TagwrapperCollection $tagwrapperFactory
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Locale\Format $priceFormat
     * @param \Magento\Framework\DataObjectFactory $objectFactory
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     * @param \Magedelight\Bundlediscount\Helper\Data $bundleHelper
     * @param \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Tax\Model\CalculationFactory $calculationFactory,
        \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $tagcategoriesFactory,
        \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory $tagwrapperFactory,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Locale\Format $priceFormat,
        \Magento\Framework\DataObjectFactory $objectFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Tax\Helper\Data $taxHelper,
        \Magento\Catalog\Helper\Data $catalogHelper,
        \Magedelight\Bundlediscount\Helper\Data $bundleHelper,
        \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount,
        array $data = []
    ) {
        $this->_calculationFactory = $calculationFactory;
        $this->objectFactory = $objectFactory;
        $this->tagcategoriesFactory = $tagcategoriesFactory;
        $this->tagwrapperFactory = $tagwrapperFactory;
        $this->jsonEncoder = $jsonEncoder;
        $this->_priceFormat = $priceFormat;
        $this->_taxHelper = $taxHelper;
        $this->_catalogData = $catalogHelper;
        $this->priceHelper = $priceHelper;
        $this->bundleHelper = $bundleHelper;
        $this->bundlediscount = $bundlediscount;
        parent::__construct($context, $data);
        $searchElement = htmlentities($this->getRequest()->getParam("qbundle"));
        $tagElement = $this->getRequest()->getParam("tag_id");
        $bundleCollection = $this->bundlediscount->getBundlesByCustomer($searchElement, $tagElement);
        $this->setCollection($bundleCollection);
    }

    public function _prepareLayout()
    {
        $linkTitle = $this->bundleHelper->getLinkTitle();
        $this->pageConfig->getTitle()->set(__($linkTitle));
        parent::_prepareLayout();

        if ($this->getCollection()) {
            $toolbar = $this->getToolbarBlock();

            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'list.pager'
            )->setCollection(
                $this->getCollection()
            );
            $toolbar->setChild('product_list_toolbar_pager', $pager);

            $collection = $this->getCollection();

            $orders = $this->getAvailableOrders();

            if ($orders) {
                $toolbar->setAvailableOrders($orders);
            }
            $sort = $this->getSortBy();
            
            if ($sort) {
                $toolbar->setDefaultOrder($sort);
            }
            $dir = $this->getDefaultDirection();
            if ($dir) {
                $toolbar->setDefaultDirection($dir);
            }
            $modes = $this->getModes();
            if ($modes) {
                $toolbar->setModes($modes);
            }
            $toolbar->setCollection($collection);

            $this->setChild('toolbar', $toolbar);
            $this->getCollection()->load();
        }

        return $this;
    }
    
    public function getSearchItems()
    {
        return htmlentities($this->getRequest()->getParam("qbundle"));
    }

    public function getTagItems()
    {
        return $this->getRequest()->getParam("tag_id");
    }

    public function getLoadedBundles()
    {
        return $this->getCollection();
    }

    public function getToolbarHtml()
    {
         return $this->getChildHtml('toolbar');
    }

    public function getToolbarBlock()
    {
        $blockName = $this->getToolbarBlockName();
        if ($blockName) {
            $block = $this->getLayout()->getBlock($blockName);
            if ($block) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, uniqid(microtime()));
        return $block;
    }

    public function getAvailableOrders()
    {
        return [
            'sort_order' => __('Position'),
            'name' => __('Bundle Name'),
            'created_at' => __('Latest'),
        ];
    }

    public function getSortBy()
    {
        return 'created_at';
    }

    public function getDefaultDirection()
    {
        return 'asc';
    }

    public function getProductJsonConfig($product)
    {
        $calculator = $this->_calculationFactory->create();

        $_request = $calculator->getRateRequest(false, false, false);

        $_request->setProductClassId($product->getTaxClassId());
        $defaultTax = $calculator->getRate($_request);

        $_request = $calculator->getRateRequest();
        $_request->setProductClassId($product->getTaxClassId());
        $currentTax = $calculator->getRate($_request);

        $_regularPrice = $product->getPrice();
        $_finalPrice = $product->getFinalPrice();
        if ($product->getTypeId() == 'bundle') {
            $_priceInclTax = $this->_catalogData->getTaxPrice(
                $product,
                $_finalPrice,
                true,
                null,
                null,
                null,
                null,
                null,
                false
            );
            $_priceExclTax = $this->_catalogData->getTaxPrice(
                $product,
                $_finalPrice,
                false,
                null,
                null,
                null,
                null,
                null,
                false
            );
        } else {
            $_priceInclTax = $this->_catalogData->getTaxPrice($product, $_finalPrice, true);
            $_priceExclTax = $this->_catalogData->getTaxPrice($product, $_finalPrice);
        }
        $_tierPrices = [];
        $_tierPricesInclTax = [];
        foreach ($product->getTierPrice() as $tierPrice) {
            $_tierPrices[] = $this->priceHelper->currency(
                $this->_catalogData->getTaxPrice(
                    $product,
                    (float) $tierPrice['website_price'],
                    false
                ) - $_priceExclTax,
                false,
                false
            );
            $_tierPricesInclTax[] = $this->priceHelper->currency(
                $this->_catalogData->getTaxPrice(
                    $product,
                    (float) $tierPrice['website_price'],
                    true
                ) - $_priceInclTax,
                false,
                false
            );
        }
        $config = [
            'productId' => $product->getId(),
            'priceFormat' => $this->_priceFormat->getPriceFormat(),
            'includeTax' => $this->_taxHelper->priceIncludesTax() ? 'true' : 'false',
            'showIncludeTax' => $this->_taxHelper->displayPriceIncludingTax(),
            'showBothPrices' => $this->_taxHelper->displayBothPrices(),
            'productPrice' => $this->priceHelper->currency($_finalPrice, false, false),
            'productOldPrice' => $this->priceHelper->currency($_regularPrice, false, false),
            'priceInclTax' => $this->priceHelper->currency($_priceInclTax, false, false),
            'priceExclTax' => $this->priceHelper->currency($_priceExclTax, false, false),
            /*
             * @var skipCalculate
             * @deprecated after 1.5.1.0
             */
            'skipCalculate' => ($_priceExclTax != $_priceInclTax ? 0 : 1),
            'defaultTax' => $defaultTax,
            'currentTax' => $currentTax,
            'idSuffix' => '_clone',
            'oldPlusDisposition' => 0,
            'plusDisposition' => 0,
            'plusDispositionTax' => 0,
            'oldMinusDisposition' => 0,
            'minusDisposition' => 0,
            'tierPrices' => $_tierPrices,
            'tierPricesInclTax' => $_tierPricesInclTax,
        ];

        $responseObject = $this->objectFactory->create();
        $this->_eventManager->dispatch('catalog_product_view_config', ['response_object' => $responseObject]);
        if (is_array($responseObject->getAdditionalOptions())) {
            foreach ($responseObject->getAdditionalOptions() as $option => $value) {
                $config[$option] = $value;
            }
        }
        return $this->jsonEncoder->encode($config);
    }
   
    public function getTagwrapperCollection($categoryId)
    {
        if ($categoryId) {
            $gwcollection = $this->tagwrapperFactory->create()->addFieldToFilter('is_active', 1)
                ->addFieldToFilter('category', ['eq' => $categoryId]);
            return $gwcollection;
        }
    }

    public function getTagcategoriesCollection()
    {
        $gccollection = $this->tagcategoriesFactory->create()->addFieldToFilter('is_active', 1);
        return $gccollection;
    }

    public function isModuleEnable()
    {
        $isEnabled =  $this->bundleHelper->isEnableFrontend();
        return $isEnabled;
    }

    public function getTagUrl()
    {
        return $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
    }

    public function getBundleAddToCartUrl($bundleId)
    {
        return $this->bundleHelper->getBundleAddToCartUrl($bundleId);
    }

    public function calculateDiscountAmount($bundle)
    {
        return $this->bundlediscount->calculateDiscountAmount($bundle);
    }

    public function getPriceHelper()
    {
        return $this->priceHelper;
    }
}
