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

namespace Magedelight\Bundlediscount\Model;

use Magento\Customer\Model\SessionFactory as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Asset\NotationResolver\Variable;

class Bundlediscount extends \Magento\Framework\Model\AbstractModel
{
    private $orderVar = 'product_list_order';
    private $limitVar = 'product_list_limit';
    private $dirVar = 'product_list_dir';
    private $dirValue = 'asc';
    private $limitValue = 9;
    private $pageVar = 'p';
    private $pageValue = '1';
    private $orderValue = 'created_at';
    private $customerSession;
    private $request;

    /**
     * Bundlediscount constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param CustomerSession $customerSession
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @param \Magento\Framework\App\Request\Http $request
     * @param Bundleitems $bundleitems
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param BundlediscountFactory $bundlediscountFactory
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     * @param \Magedelight\Bundlediscount\Helper\Data $helper
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magedelight\Bundlediscount\Block\ProductList\Toolbar $toolbar
     * @param \Magento\Catalog\Helper\ImageFactory $imageHelperFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        CustomerSession $customerSession,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Helper\Data $catalogHelper,
        \Magento\Tax\Helper\Data $taxHelper,
        \Magento\Framework\App\Request\Http $request,
        \Magedelight\Bundlediscount\Model\Bundleitems $bundleitems,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magedelight\Bundlediscount\Model\BundlediscountFactory $bundlediscountFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magedelight\Bundlediscount\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Checkout\Model\Cart $cart,
        \Magedelight\Bundlediscount\Block\ProductList\Toolbar $toolbar,
        \Magento\Catalog\Helper\ImageFactory $imageHelperFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->cart = $cart;
        $this->date = $dateTime;
        $this->_helper = $helper;
        $this->_catalogData = $catalogHelper;
        $this->_taxHelper = $taxHelper;
        $this->productFactory = $productFactory;
        $this->priceHelper = $priceHelper;
        $this->bundleItems = $bundleitems;
        $this->toolbarBlock = $toolbar;
        $this->request = $request;
        $this->bundlediscountFactory = $bundlediscountFactory;
        $this->customerSession = $customerSession;
        $this->imageHelperFactory = $imageHelperFactory;
        $this->_storeManager = $storeManager;
        $this->messageManager = $messageManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function _construct()
    {
        $this->limitValue = $this->toolbarBlock->getLimit();
        $params = $this->request->getParams();

        if (isset($params[$this->orderVar])) {
            $this->orderValue = $params[$this->orderVar];
        }
        if (isset($params[$this->limitVar])) {
            $this->limitValue = $params[$this->limitVar];
        }
        if (isset($params[$this->dirVar])) {
            $this->dirValue = $params[$this->dirVar];
        }
        if (isset($params[$this->pageVar])) {
            $this->pageValue = $params[$this->pageVar];
        }

        $this->_init('Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount');
    }

    /**
     * @param $identifier
     * @param $storeId
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }

    protected function _afterLoad()
    {
        parent::_afterLoad();

        $displayBothPrice = (boolean) $this->_taxHelper->displayBothPrices();
        $displayIncludeTaxPrice = (boolean) $this->_taxHelper->displayPriceIncludingTax();

        $product = $this->productFactory->create()->load($this->getProductId());

        if ($displayBothPrice) {
            $finalPrice = $this->_catalogData->getTaxPrice(
                $product,
                $product->getFinalPrice(),
                true,
                null,
                null,
                null,
                null,
                null,
                false
            );
        } else {
            if ($displayIncludeTaxPrice) {
                $finalPrice = $this->_catalogData->getTaxPrice(
                    $product,
                    $product->getFinalPrice(),
                    true,
                    null,
                    null,
                    null,
                    null,
                    null,
                    false
                );
            } else {
                $finalPrice = $this->_catalogData->getTaxPrice(
                    $product,
                    $product->getFinalPrice(),
                    false,
                    null,
                    null,
                    null,
                    null,
                    null,
                    false
                );
            }
        }

        $this->setProductName($product->getName())
                ->setProductPrice($finalPrice)
                ->setProductSku($product->getSku())
                ->setImageUrl($this->getImage($product))
                ->setTypeId($product->getTypeId())
                ->setHasCustomOptions($product->getOptions() ? 1 : 0)
                ->setIsSalable(($product->isSalable()) ? 1 : 0)
                ->setProductUrl($product->getProductUrl());

        $items = $this->bundleItems->getItemsByBundle($this->getBundleId());
        $this->setSelections($items);

        return $this;
    }

    /**
     * @return \Magento\Customer\Model\Session
     */
    private function getCustomerSession()
    {
        return $this->customerSession->create();
    }

    /**
     * @param $customer
     * @param null $serch_element
     * @param null $tag_element
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBundlesByCustomer($serch_element = null, $tag_element = null)
    {
        $customerSession = $this->getCustomerSession();
        $customerGroup = (!$customerSession) ? 0 : $customerSession->getCustomerGroupId();
        $tag_elements =  explode(',', $tag_element);
        $valueFilter = [];

        foreach ($tag_elements as $value) {
             $valueFilter[] = (['finset' => [$value]]);
        }

        if ($tag_element) {
            $bundleCollection = $this->getCollection()
                ->addFieldToFilter('status', ['eq' => 1])
                ->addFieldToFilter('store_ids', [['finset' => [0]],
                    ['finset' => [$this->_storeManager->getStore()->getId()]]])
                ->addFieldToFilter('customer_groups', ['finset' => [$customerGroup]])
                ->addfieldtofilter('date_from', [
                    ['to' => $this->date->gmtDate('Y-m-d')],
                    ['date_from', 'null' => ''], ])
                ->addfieldtofilter('date_to', [
                    ['gteq' => $this->date->gmtDate('Y-m-d')],
                    ['date_to', 'null' => ''], ])
               ->addFieldToFilter(
                   ['bundle_keywords', 'name'],
                   [
                    ['like'=>'%'.$serch_element.'%'],
                    ['like'=>'%'.$serch_element.'%']
                   ]
               );

               $bundleCollection->addFieldToFilter(
                   'bundle_tags',
                   $valueFilter
               );

                $bundleCollection->setCurPage($this->pageValue)
                ->setPageSize($this->limitValue)
                ->setOrder($this->orderValue, strtoupper($this->dirValue));
        } else {
            $bundleCollection = $this->getCollection()
            ->addFieldToFilter('status', ['eq' => 1])
            ->addFieldToFilter('store_ids', [['finset' => [0]],
                ['finset' => [$this->_storeManager->getStore()->getId()]]])
            ->addFieldToFilter('customer_groups', ['finset' => [$customerGroup]])
            ->addfieldtofilter('date_from', [
             ['to' => $this->date->gmtDate('Y-m-d')],
             ['date_from', 'null' => ''], ])
            ->addfieldtofilter('date_to', [
             ['gteq' => $this->date->gmtDate('Y-m-d')],
             ['date_to', 'null' => ''], ])
            ->addFieldToFilter(
                ['bundle_keywords', 'name'],
                [
                ['like'=>'%'.$serch_element.'%'],
                ['like'=>'%'.$serch_element.'%']
                ]
            )
            ->setCurPage($this->pageValue)
            ->setPageSize($this->limitValue)
            ->setOrder($this->orderValue, strtoupper($this->dirValue));
        }

        $displayBothPrice = (boolean) $this->_taxHelper->displayBothPrices();
        $displayIncludeTaxPrice = (boolean) $this->_taxHelper->displayPriceIncludingTax();
        foreach ($bundleCollection as $bundle) {
            $product = $this->productFactory->create()->setStoreId($this->_storeManager->getStore()->getId())
                ->load($bundle->getProductId());

            if ($displayBothPrice) {
                $finalPrice = $this->_catalogData->getTaxPrice(
                    $product,
                    $product->getFinalPrice(),
                    true,
                    null,
                    null,
                    null,
                    null,
                    null,
                    false
                );
            } else {
                if ($displayIncludeTaxPrice) {
                    $finalPrice = $this->_catalogData->getTaxPrice(
                        $product,
                        $product->getFinalPrice(),
                        true,
                        null,
                        null,
                        null,
                        null,
                        null,
                        false
                    );
                } else {
                    $finalPrice = $this->_catalogData->getTaxPrice(
                        $product,
                        $product->getFinalPrice(),
                        false,
                        null,
                        null,
                        null,
                        null,
                        null,
                        false
                    );
                }
            }
            $bundleCollection->getItemByColumnValue('bundle_id', $bundle->getId())
                    ->setProductName($product->getName())
                    ->setProductPrice($finalPrice)
                    ->setProductSku($product->getSku())
                    ->setImageUrl($this->getImage($product))
                    ->setTypeId($product->getTypeId())
                    ->setHasCustomOptions($product->getOptions() ? 1 : 0)
                    ->setIsSalable(($product->isSalable()) ? 1 : 0)
                    ->setProductUrl($product->getProductUrl());

            $items = $this->bundleItems->getItemsByBundle($bundle->getId());

            $bundleCollection->getItemByColumnValue('bundle_id', $bundle->getId())->setSelections($items);
        }
        return ($bundleCollection->count() > 0) ? $bundleCollection : null;
    }

    /**
     * @param $customer
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBundlesByCustomerCrosssell()
    {
        $bundleparentid = [];
        $bundleparentidsingle = [];
        $bundleCollection = '';
        $cart = $this->cart->getQuote();
        $bundleids = $cart->getData('bundle_ids');
        if (!empty($bundleids)) {
            $bundleidsary = explode(',', $bundleids);
            for ($v = 0; $v < count($bundleidsary); ++$v) {
                $bundleparentid[] = $this->load($bundleidsary[$v])->getProductId();
            }
        }
        foreach ($cart->getAllItems() as $item) {
            $bundleparentidsingle[] = $item->getData('product_id');
        }
        $bundleparentid = array_unique(array_merge($bundleparentid, $bundleparentidsingle));
        $customerSession = $this->getCustomerSession();
        $customerGroup = (!$customerSession) ? 0 : $customerSession->getCustomerGroupId();

        $bundleCollection = $this->getCollection()
                ->addFieldToFilter('status', ['eq' => 1])
                ->addFieldToFilter('product_id', ['in' => $bundleparentid])
                ->addFieldToFilter('store_ids', [['finset' => [0]],
                    ['finset' => [$this->_storeManager->getStore()->getId()]]])
                ->addFieldToFilter('customer_groups', ['finset' => [$customerGroup]])
                ->addfieldtofilter('date_from', [
                    ['to' => $this->date->gmtDate('Y-m-d')],
                    ['date_from', 'null' => ''], ])
                ->addfieldtofilter('date_to', [
                    ['gteq' => $this->date->gmtDate('Y-m-d')],
                    ['date_to', 'null' => ''], ])
                ->setCurPage($this->pageValue)
                ->setPageSize($this->limitValue)
                ->setOrder($this->orderValue, strtoupper($this->dirValue));

        !empty($bundleidsary) ? $bundleCollection->addFieldToFilter('bundle_id', ['nin' => $bundleidsary]) : '';        //92
        $displayBothPrice = (boolean) $this->_taxHelper->displayBothPrices();
        $displayIncludeTaxPrice = (boolean) $this->_taxHelper->displayPriceIncludingTax();

        if ($bundleCollection->count() > 0) {
            foreach ($bundleCollection as $bundle) {
                $product = $this->productFactory->create()->setStoreId($this->_storeManager->getStore()->getId())
                    ->load($bundle->getProductId());

                if ($displayBothPrice) {
                    $finalPrice = $this->_catalogData->getTaxPrice(
                        $product,
                        $product->getFinalPrice(),
                        true,
                        null,
                        null,
                        null,
                        null,
                        null,
                        false
                    );
                } else {
                    if ($displayIncludeTaxPrice) {
                        $finalPrice = $this->_catalogData->getTaxPrice(
                            $product,
                            $product->getFinalPrice(),
                            true,
                            null,
                            null,
                            null,
                            null,
                            null,
                            false
                        );
                    } else {
                        $finalPrice = $this->_catalogData->getTaxPrice(
                            $product,
                            $product->getFinalPrice(),
                            false,
                            null,
                            null,
                            null,
                            null,
                            null,
                            false
                        );
                    }
                }

                $bundleCollection->getItemByColumnValue('bundle_id', $bundle->getId())
                        ->setProductName($product->getName())
                        ->setProductPrice($finalPrice)
                        ->setProductSku($product->getSku())
                        ->setImageUrl($this->getImage($product))
                        ->setTypeId($product->getTypeId())
                        ->setHasCustomOptions($product->getOptions() ? 1 : 0)
                        ->setIsSalable(($product->isSalable()) ? 1 : 0)
                        ->setProductUrl($product->getProductUrl());

                $items = $this->bundleItems->getItemsByBundle($bundle->getBundleId());
                $bundleCollection->getItemByColumnValue('bundle_id', $bundle->getId())->setSelections($items);
            }
        }
         return $bundleCollection;
    }

    /**
     * @param $product
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|void|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBundlesByProduct($product)
    {
        $customerSession = $this->getCustomerSession();
        $customerGroup = (!$customerSession) ? 0 : $customerSession->getCustomerGroupId();
        try {
            if ($product->getId()) {
                $bundleCollection = $this->getCollection()
                        ->addFieldToFilter('product_id', ['eq' => $product->getId()])
                        ->addFieldToFilter('status', ['eq' => 1])
                        ->addFieldToFilter('store_ids', [['finset' => [0]],
                            ['finset' => [$this->_storeManager->getStore()->getId()]]])
                        ->addFieldToFilter('customer_groups', ['finset' => [$customerGroup]])
                        ->addfieldtofilter('date_from', [
                            ['to' => $this->date->gmtDate('Y-m-d')],
                            ['date_from', 'null' => ''], ])
                        ->addfieldtofilter('date_to', [
                            ['gteq' => $this->date->gmtDate('Y-m-d')],
                            ['date_to', 'null' => ''], ])
                        ->setCurPage($this->pageValue)
                        ->setPageSize($this->limitValue)
                        ->setOrder($this->orderValue, strtoupper($this->dirValue));
                $displayBothPrice = (boolean) $this->_taxHelper->displayBothPrices();
                $displayIncludeTaxPrice = (boolean) $this->_taxHelper->displayPriceIncludingTax();
                try {
                    if ($bundleCollection->count() > 0) {
                        foreach ($bundleCollection as $bundle) {
                                $product = $this->productFactory->create()->setStoreId($this->_storeManager->getStore()->getId())
                                    ->load($bundle->getProductId());

                            if ($displayBothPrice) {
                                $finalPrice = $this->_catalogData->getTaxPrice(
                                    $product,
                                    $product->getFinalPrice(),
                                    true,
                                    null,
                                    null,
                                    null,
                                    null,
                                    null,
                                    false
                                );
                            } else {
                                if ($displayIncludeTaxPrice) {
                                    $finalPrice = $this->_catalogData->getTaxPrice(
                                        $product,
                                        $product->getFinalPrice(),
                                        true,
                                        null,
                                        null,
                                        null,
                                        null,
                                        null,
                                        false
                                    );
                                } else {
                                    $finalPrice = $this->_catalogData->getTaxPrice(
                                        $product,
                                        $product->getFinalPrice(),
                                        false,
                                        null,
                                        null,
                                        null,
                                        null,
                                        null,
                                        false
                                    );
                                }
                            }

                                $bundleCollection->getItemByColumnValue('bundle_id', $bundle->getId())
                                        ->setProductName($product->getName())
                                        ->setProductPrice($finalPrice)
                                        ->setProductSku($product->getSku())
                                        ->setImageUrl($this->getImage($product))
                                        ->setTypeId($product->getTypeId())
                                        ->setHasCustomOptions($product->getOptions() ? 1 : 0)
                                        ->setIsSalable(($product->isSalable()) ? 1 : 0)
                                        ->setProductUrl($product->getProductUrl());

                                $items = $this->bundleItems->getItemsByBundle($bundle->getId());
                                $bundleCollection->getItemByColumnValue('bundle_id', $bundle->getId())->setSelections($items);
                        }
                    }
                    return $bundleCollection;

                } catch (\Exception $e) {
                            $this->messageManager->addError(
                                __($e->getMessage())
                            );
                }

            }
        } catch (\Exception $e) {
            $this->messageManager->addError(
                __($e->getMessage())
            );
        }
    }

    /**
     * @param array $bundleIds
     * @param null $object
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|void|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBundleObjects($bundleIds = [], $object = null)
    {
        if (!is_array($bundleIds)) {
            $bundleIds = [$bundleIds];
        }

        $customerSession = $this->getCustomerSession();
        $customerGroup = (!$customerSession) ? 0 : $customerSession->getCustomerGroupId();
        try {
            if (count($bundleIds)) {
                $bundleCollection = $this->getCollection()
                           ->addFieldToFilter('status', ['eq' => 1])
                           ->addFieldToFilter('store_ids', [['finset' => [0]],
                               ['finset' => [$this->_storeManager->getStore()->getId()]]])
                           ->addFieldToFilter('customer_groups', ['finset' => [$customerGroup]])
                           ->addfieldtofilter('date_from', [
                               ['to' => $this->date->gmtDate('Y-m-d')],
                               ['date_from', 'null' => ''], ])
                           ->addfieldtofilter('date_to', [
                               ['gteq' => $this->date->gmtDate('Y-m-d')],
                               ['date_to', 'null' => ''], ])
                           ->addFieldToFilter('bundle_id', ['in' => $bundleIds])
                           ->setCurPage($this->pageValue)
                           ->setPageSize($this->limitValue)
                           ->setOrder($this->orderValue, strtoupper($this->dirValue));

                $displayBothPrice = (boolean) $this->_taxHelper->displayBothPrices();
                $displayIncludeTaxPrice = (boolean) $this->_taxHelper->displayPriceIncludingTax();
                try {
                    if ($bundleCollection->count() > 0) {
                        foreach ($bundleCollection as $bundle) {
                                   $product = $this->productFactory->create()->setStoreId($this->_storeManager->getStore()->getId())
                                        ->load($bundle->getProductId());
                            if ($displayBothPrice) {
                                $finalPrice = $this->_catalogData->getTaxPrice(
                                    $product,
                                    $product->getFinalPrice(),
                                    true,
                                    null,
                                    null,
                                    null,
                                    null,
                                    null,
                                    false
                                );
                            } else {
                                if ($displayIncludeTaxPrice) {
                                             $finalPrice = $this->_catalogData->getTaxPrice(
                                                 $product,
                                                 $product->getFinalPrice(),
                                                 true,
                                                 null,
                                                 null,
                                                 null,
                                                 null,
                                                 null,
                                                 false
                                             );
                                } else {
                                        $finalPrice = $this->_catalogData->getTaxPrice(
                                            $product,
                                            $product->getFinalPrice(),
                                            false,
                                            null,
                                            null,
                                            null,
                                            null,
                                            null,
                                            false
                                        );
                                }
                            }

                                    $bundleCollection->getItemByColumnValue('bundle_id', $bundle->getId())
                                            ->setProductName($product->getName())
                                            ->setProductPrice($finalPrice)
                                            ->setProductSku($product->getSku())
                                            ->setImageUrl($this->getImage($product))
                                            ->setTypeId($product->getTypeId())
                                            ->setHasCustomOptions($product->getOptions() ? 1 : 0)
                                            ->setIsSalable(($product->isSalable()) ? 1 : 0)
                                            ->setProductUrl($product->getProductUrl());

                                    $items = $this->bundleItems->getItemsByBundle($bundle->getBundleId());
                                    $bundleCollection->getItemByColumnValue('bundle_id', $bundle->getId())->setSelections($items);

                            if ($object && $object->count() > 0) {
                                $object->addItem($bundleCollection->getItemByColumnValue('bundle_id', $bundle->getId())
                                ->setSelections($items));
                            }
                        }

                         return $bundleCollection;
                    }

                } catch (\Exception $e) {
                      $this->messageManager->addError(
                          __($e->getMessage())
                      );
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(
                __($e->getMessage())
            );
        }

            // if ($object && $object->count() > 0) {
            //     return $object;
            // } else {
            //     return ($bundleCollection->count() > 0) ? $bundleCollection : null;
            // }
    }

    /**
     * @param $bundle
     * @return array
     */
    public function calculateDiscountAmount($bundle)
    {
        $result = ['total_amount' => 0.00, 'discount_amount' => 0.00, 'final_amount' => 0.00, 'discount_label' => 0.0];
        $totalAmount = 0.00;
        $discountAmount = 0.00;
        $finalAmount = 0.00;
        $discountLabel = '';
        if ($bundle->getId()) {
            $ignoreBaseProductFromDiscount = $bundle->getExcludeBaseProduct();
            $baseProductTotalAmount = $bundle->getProductPrice() * $bundle->getQty();
            $totalAmount += $baseProductTotalAmount;

            if ($bundle->getSelections() != null) {
                foreach ($bundle->getSelections() as $_selection) {
                    $totalAmount += ($_selection->getQty() * $_selection->getPrice());
                }
            }

            if ($bundle->getDiscountType() == 0) {
                $discountLabel = $this->priceHelper->currency(
                    $bundle->getDiscountPrice(),
                    true,
                    false
                );
                $discountAmount = (float) $bundle->getDiscountPrice();
            } else {
                $discountCalculationBase = $totalAmount;
                if ($ignoreBaseProductFromDiscount) {
                    $discountCalculationBase = $totalAmount - $baseProductTotalAmount;
                }
                $discountAmount = ($bundle->getDiscountPrice() * $discountCalculationBase) / 100;
                $discountLabel = $this->_helper->formatPercentage($bundle->getDiscountPrice()).'%';
            }

            if ($discountAmount > $totalAmount) {
                $discountAmount = $totalAmount;
            }
            $finalAmount = $totalAmount - $discountAmount;
            $result['total_amount'] = $totalAmount;
            $result['discount_amount'] = $discountAmount;
            $result['final_amount'] = $finalAmount;
            $result['discount_label'] = $discountLabel;
        }
        return $result;
    }

    /**
     * @param $bundle
     * @param $product
     * @return array
     */
    public function calculateDiscountAmountByProductId($bundle, $product)
    {
        $bundle = $this->bundlediscountFactory->create()->load($bundle);

        $result = ['total_amount' => 0.00, 'discount_amount' => 0.00, 'final_amount' => 0.00, 'discount_label' => 0.0];
        $totalAmount = 0.00;
        $discountAmount = 0.00;
        $finalAmount = 0.00;
        $discountLabel = '';
        if ($bundle->getId()) {
            $excludeFromBaseProductFlag = $bundle->getExcludeBaseProduct();
            if (!$excludeFromBaseProductFlag) {
                $totalAmount += $bundle->getProductPrice() * $bundle->getQty();
            }
            if ($bundle->getSelections() != null) {
                foreach ($bundle->getSelections() as $_selection) {
                    if (in_array($_selection->getProductId(), $product)) {
                        $totalAmount += ($_selection->getQty() * $_selection->getPrice());
                    }
                }
            }

            if ($bundle->getDiscountType() == 0) {
                $discountLabel = $this->priceHelper->currency(
                    $bundle->getDiscountPrice(),
                    true,
                    false
                );
                $discountAmount = (float) $bundle->getDiscountPrice();
            } else {
                $discountAmount = ($bundle->getDiscountPrice() * $totalAmount) / 100;
                $discountLabel = $this->_helper->formatPercentage($bundle->getDiscountPrice()).'%';
            }

            if ($discountAmount > $totalAmount) {
                $discountAmount = $totalAmount;
            }
            $finalAmount = $totalAmount - $discountAmount;
            $result['total_amount'] = $totalAmount;
            $result['discount_amount'] = $discountAmount;
            $result['final_amount'] = $finalAmount;
            $result['discount_label'] = $discountLabel;
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function canShowAddToCartButton()
    {
        $canshow = true;
        $selections = $this->getSelections();
        if ($this->getIsSalable() == 0) {
            $canshow = false;
        }
        if ($canshow) {
            if ($selections != null) {
                foreach ($selections as $_selection) {
                    if ($_selection->getIsSalable() == 0) {
                        $canshow = false;
                        break;
                    }
                }
            }
        }
        return $canshow;
    }

    /**
     * @param $product
     * @return string
     */
    public function getImage($product)
    {
        $imageUrl = $this->imageHelperFactory->create()->init($product, 'category_page_list')
            ->constrainOnly(false)->keepTransparency(true)->keepAspectRatio(true)
            ->keepFrame(true)->backgroundColor([255, 255, 255])->resize(90, 90)->getUrl();
        return $imageUrl;
    }

    /**
     * @param bool $excludeSelection
     * @return bool
     */
    public function hasOptions($excludeSelection = false)
    {
        $hasOptions = false;
        $productTypes = ['grouped', 'configurable', 'bundle', 'downloadable'];
        $selections = $this->getSelections();

        if (in_array($this->getTypeId(), $productTypes)) {
            $hasOptions = true;
        }
        if ($selections != null) {
            if (!$hasOptions && !$excludeSelection) {
                foreach ($selections as $_selection) {
                    if (in_array($_selection->getTypeId(), $productTypes)) {
                        $hasOptions = true;
                        break;
                    }
                }
            }
        }
        return $hasOptions;
    }

    /**
     * @param bool $excludeSelection
     * @return bool
     */
    public function hasCustomOptions($excludeSelection = false)
    {
        $hasCustomOptions = false;
        $selections = $this->getSelections();

        if ($this->getHasCustomOptions() == 1) {
            $hasCustomOptions = true;
        }
        if (!$hasCustomOptions && !$excludeSelection) {
            if ($selections != null) {
                foreach ($selections as $_selection) {
                    if ($_selection->getHasCustomOptions() == 1) {
                        $hasCustomOptions = true;
                        break;
                    }
                }
            }
        }
        return $hasCustomOptions;
    }

    /**
     * @param $bundleIds
     * @return array
     */
    public function calculateProductQtys($bundleIds)
    {
        if (!is_array($bundleIds)) {
            $bundleIds = [$bundleIds];
        }
        try {
            $result = [];
            foreach ($bundleIds as $id) {
                $bundle = $this->load($id);
                if (!isset($result[$bundle->getProductId()])) {
                    $result[$bundle->getProductId()][$id] = $bundle->getQty();
                } else {
                    $result[$bundle->getProductId()][$id] = $bundle->getQty();
                }
                try {
                    if ($bundle->getSelections() != null) {
                        foreach ($bundle->getSelections() as $_selection) {
                            if (!isset($result[$_selection->getProductId()])) {
                                $result[$_selection->getProductId()][$id] = $_selection->getQty();
                            } else {
                                $result[$_selection->getProductId()][$id] = $_selection->getQty();
                            }
                        }
                    }

                } catch (\Exception $e) {
                    $this->messageManager->addError(
                        __($e->getMessage())
                    );
                }

            }
            return $result;
        } catch (\Exception $e) {
                $this->messageManager->addError(
                    __($e->getMessage())
                );
        }
    }
}
