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

namespace Magedelight\Bundlediscount\Model\Quote;

class Discount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    private $quoteValidator = null;
    private $qtyArrays = [];
    protected $resourceConnection;
    protected $_product;

    /**
     * Discount constructor.
     * @param \Magento\Quote\Model\QuoteValidator $quoteValidator
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount
     * @param \Magedelight\Bundlediscount\Helper\Data $helperData
     * @param \Magento\Tax\Helper\Data $taxHelperData
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount,
        \Magedelight\Bundlediscount\Helper\Data $helperData,
        \Magento\Tax\Helper\Data $taxHelperData,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->quoteValidator = $quoteValidator;
        $this->storeManager = $storeManager;
        $this->currencyFactory = $currencyFactory;
        $this->helper = $helperData;
        $this->priceCurrency = $priceCurrency;
        $this->taxHelper = $taxHelperData;
        $this->bundlediscount = $bundlediscount;
        $this->resourceConnection = $resourceConnection;
        $this->productFactory = $productFactory;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|\Magento\Quote\Model\Quote\Address\Total\AbstractTotal
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        /*If quote doesn't include bundle id the return directly default mechanism
        this solutions we had implemented for only jira ticket PBDM2-21 */
        if (empty($quote->getData('bundle_ids'))) {
            return $this;
        }

        $address = $shippingAssignment->getShipping()->getAddress();

        $label = "";
        if ($quote->getBundleIds()) {
            $label = $this->helper->getDiscountLabel();
        }

        $count = 0;
        $appliedCartDiscount = 0;
        $cartruleapplied = false;
        $totalDiscountAmount = 0;
        $subtotalWithDiscount = 0;
        $baseTotalDiscountAmount = 0;
        $baseSubtotalWithDiscount = 0;
        $cartproductids = [];

        if ($total->getDiscountAmount()) {
            $cartruleapplied = true;
            $appliedCartDiscount = $total->getDiscountAmount();
            $discountAmount = $total->getDiscountAmount();
            $totalDiscountAmount = -$total->getDiscountAmount();
        }

        $bundleIds = explode(',', $quote->getData('bundle_ids'));

        $connection  = $this->resourceConnection->getConnection();
        $tableName   = $connection->getTableName('md_bundlediscount_bundles');
        $tableName2   = $connection->getTableName('md_bundlediscount_items');
        $results = [];
        $productids = [];
        $productqty = [];
        foreach ($bundleIds as $bid) {
            $query = "SELECT product_id,qty FROM ".$tableName." WHERE bundle_id = ".$bid." ";
            $results[] = $this->resourceConnection->getConnection()->fetchAll($query);

            $queryp = "SELECT product_id,qty FROM ".$tableName2." WHERE bundle_id = ".$bid." ";
            $resultsp[] = $this->resourceConnection->getConnection()->fetchAll($queryp);
        }

        foreach ($results as $res) {
            foreach ($res as $r) {
                $productids[] = $r['product_id'];
                $productqty[] = $r['qty'];

            }
        }
        foreach ($resultsp as $resp) {
            foreach ($resp as $rp) {
                $productids[] = $rp['product_id'];
                $productqty[] = $rp['qty'];
            }
        }
        //echo "<pre>"; print_r($productqty); exit;
        $productqtys = 0;
        foreach($productqty  as $pq)
        {
            $productqtys += $pq;
        }
        //echo $productqtys; exit;
        $product = $this->productFactory->create();
        $productPriceById = [];
        $totalbundlediscountprod = 0;
        foreach ($productids as $pid) {
            $productPriceById[] = $product->load($pid)->getPrice();
            $totalbundlediscountprod += $product->load($pid)->getPrice();
        }

        if ($bundleIds[0] == '') {
            unset($bundleIds[0]);
        }

        $this->qtyArrays = $this->bundlediscount->calculateProductQtys($bundleIds);

        $items = $quote->getAllItems();

         // Get total items and total quantity in current cart
        $totalItems = $quote->getItemsCount();
        $totalQuantity = $quote->getItemsQty();
        
        $qtyAppliedDiscount =1;
        if ($totalItems >1) {
            $qtyAppliedDiscount = intval($totalQuantity / $totalItems);
        }

        if (!count($items)) {
            $address->setBundleDiscountAmount($totalDiscountAmount);
            $address->setBaseBundleDiscountAmount($baseTotalDiscountAmount);
            return $this;
        }

        $addressQtys = $this->_calculateAddressQtys($address);

        $finalBundleIds = $this->_validateBundleIds($addressQtys, $bundleIds);
        if (is_array($addressQtys) && count($addressQtys) > 0) {
            $count += array_sum(array_values($addressQtys));
        }

        $currency = $this->storeManager->getStore()->getCurrentCurrencyCode();
        $storeId =  $this->storeManager->getStore()->getStoreId();
        $currentCurrency = $this->storeManager->getStore()->getCurrentCurrency()->getCode();
        $baseCurrency = $this->storeManager->getStore()->getBaseCurrency()->getCode();

        foreach ($finalBundleIds as $id) {
            $bundle = $this->bundlediscount->load($id);
            $excludeFromBaseProductFlag = $bundle->getExcludeBaseProduct();
            $totalAmountOfBundle = 0;
            $tempArray = [];
            foreach ($items as $item) {
                $cartproductids[] = $item->getProductId();


                if ($item instanceof \Magento\Quote\Model\Quote\Address\Item) {
                    $quoteItem = $item->getAddress()->getQuote()->getItemById($item->getQuoteItemId());
                } else {
                    $quoteItem = $item;
                }
                $product = $quoteItem->getProduct();
                $product->setCustomerGroupId($quoteItem->getQuote()->getCustomerGroupId());
                if (isset($this->qtyArrays[$quoteItem->getProduct()->getId()][$id])) {
                    if (!in_array($quoteItem->getProduct()->getId(), $tempArray)) {
                        if ($excludeFromBaseProductFlag && $product->getId() == $bundle->getProductId()) {
                            continue;
                        }
                        $tempArray[] = $quoteItem->getProduct()->getId();

                        $qty = $this->qtyArrays[$quoteItem->getProduct()->getId()][$id];
                        $price = $quoteItem->getDiscountCalculationPrice();
                        $calcPrice = $quoteItem->getCalculationPrice();

                        if ($this->taxHelper->displayPriceIncludingTax()) {
                            $price = ($quoteItem->getDiscountCalculationPrice())+$quoteItem->getTaxAmount();
                            $calcPrice = ($quoteItem->getCalculationPrice())+$quoteItem->getTaxAmount();
                        }

                        $itemPrice = $price === null ? $calcPrice : $price;
                        $totalAmountOfBundle += $itemPrice * $qty;
                    }
                }
            }

            if ($bundle->getDiscountType() == 0) {
                $totalDiscountAmount += $bundle->getDiscountPrice() ;

                $baseTotalDiscountAmount += $bundle->getDiscountPrice() ;
                $totalDiscountAmount = $this->priceCurrency->convert($totalDiscountAmount, $storeId, $currency);
            } else {
                $totalDiscountAmount += ($bundle->getDiscountPrice()  * $totalAmountOfBundle) / 100;
                $baseTotalDiscountAmount += ($bundle->getDiscountPrice()  * $totalAmountOfBundle) / 100;
            }
        }

        $totalDiscountAmount = round($totalDiscountAmount, 2);
        $baseTotalDiscountAmount = round($baseTotalDiscountAmount, 2);

        if ($currentCurrency != $baseCurrency) {
            $rate = $this->currencyFactory->create()->load($currentCurrency)->getAnyRate($baseCurrency);
            $baseTotalDiscountAmount = ($baseTotalDiscountAmount * $rate);
        }

        $totaldata = $total->getData();

        $subTotal = $totaldata['subtotal'];
        $baseSubTotal = $totaldata['base_subtotal'];

        if ($this->taxHelper->displayPriceIncludingTax()) {
            $subTotal = $total->getSubtotalInclTax(); // Subtotal included tax.
            $baseSubTotal = $total->getBaseSubtotalInclTax();
        }

        if ($totalDiscountAmount > 0 && $this->taxHelper->applyTaxAfterDiscount()) {
            if ($count > 0) {
                $divided = $totalDiscountAmount / $count;
                $baseDivided = $baseTotalDiscountAmount / $count;
                foreach ($items as $item) {
                    $itemRowTotal = $item->getRowTotal();
                    $itemBaseRowTotal = $item->getBaseRowTotal();

                    $ids = array_diff($cartproductids, $productids);

                    $dis_amount = (($item->getPrice() * 100) / $totalbundlediscountprod);
                    $final_disamount = round((($dis_amount * $totalDiscountAmount) / 100), 1);

                    $dividedItemDiscount = round($totalDiscountAmount / $subTotal, 2);
                    $baseDividedItemDiscount = round($baseTotalDiscountAmount / $baseSubTotal, 2);

                    $oldDiscountAmount = $item->getDiscountAmount();
                    $oldBaseDiscountAmount = $item->getBaseDiscountAmount();
                    $origionalDiscountAmount = $item->getOriginalDiscountAmount();
                    $baseOrigionalDiscountAmount = $item->getBaseOriginalDiscountAmount();

                    if (in_array($item->getProductId(), $productids)) {
                        if ($cartruleapplied) {
                            $item->setDiscountAmount($final_disamount + $dividedItemDiscount);
                            $item->setBaseDiscountAmount($final_disamount + $baseDividedItemDiscount);
                            $item->setOriginalDiscountAmount($final_disamount + $dividedItemDiscount);
                            $item->setBaseOriginalDiscountAmount($final_disamount + $baseDividedItemDiscount);

                        } else {
                            $item->setDiscountAmount($final_disamount);
                            $item->setBaseDiscountAmount($final_disamount);
                            $item->setOriginalDiscountAmount($final_disamount);
                            $item->setBaseOriginalDiscountAmount($final_disamount);
                        }
                    }
                }
            }
        }

        $address->setBundleDiscountAmount($totalDiscountAmount);

        $address->setBaseBundleDiscountAmount($baseTotalDiscountAmount);
        $quote->setBundleDiscountAmount($totalDiscountAmount);
        $quote->setBaseBundleDiscountAmount($baseTotalDiscountAmount);

        $discountAmount = -$totalDiscountAmount;
        $baseTotalDiscountAmount = -$baseTotalDiscountAmount;

        if ($total->getDiscountDescription()) {
            // If a discount exists in cart and another discount is applied, the add both discounts.
            $label = !empty($label) ? $label = ", ".$label : $label;
            $appliedCartDiscount = $total->getDiscountAmount();
            $discountAmount = ($total->getDiscountAmount() + $discountAmount) - $total->getDiscountAmount();
            $baseTotalDiscountAmount = ($total->getBaseDiscountAmount() + $baseTotalDiscountAmount) - $total->getBaseDiscountAmount();
            $label = $total->getDiscountDescription().$label;
        }

        $getSubTotal = $total->getSubtotal();
        $getBaseSubtotal = $total->getBaseSubtotal();

        if ($this->taxHelper->displayPriceIncludingTax()) {
            $getSubTotal = round($total->getSubtotalInclTax(), 2); // Subtotal included tax.
            $getBaseSubtotal = round($total->getBaseSubtotalInclTax(), 2);
        }

        $tempDiscount = str_replace('-', '', $discountAmount);
        if ($tempDiscount > $getSubTotal) {
            $discountAmount = '-'.$getSubTotal;
            $baseTotalDiscountAmount = '-'.$getBaseSubtotal;
        }
        $total->setDiscountDescription($label);
        $total->setDiscountAmount($discountAmount);
        $total->setBaseDiscountAmount($baseTotalDiscountAmount);
        $total->setSubtotalWithDiscount($getSubTotal + $discountAmount);

        $total->setBaseSubtotalWithDiscount($getBaseSubtotal + $baseTotalDiscountAmount);
        if (isset($appliedCartDiscount)) {
            $total->addTotalAmount($this->getCode(), $discountAmount - $appliedCartDiscount);
            $total->addBaseTotalAmount($this->getCode(), $baseTotalDiscountAmount - $appliedCartDiscount);
        } else {
            $total->addTotalAmount($this->getCode(), $discountAmount);
            $total->addBaseTotalAmount($this->getCode(), $baseTotalDiscountAmount);
        }
        return $this;
    }

    private function _calculateAddressQtys(\Magento\Quote\Model\Quote\Address $address)
    {
        $result = [];
        $keys = array_keys($this->qtyArrays);
        foreach ($address->getAllVisibleItems() as $item) {
            if (!isset($result[$item->getProductId()])) {
                $result[$item->getProductId()] = $item->getQty();
            } else {
                $result[$item->getProductId()] += $item->getQty();
            }
        }
        foreach ($keys as $productId) {
            if (!isset($result[$productId])) {
                $result[$productId] = 0;
            }
        }
        return $result;
    }

    private function _validateBundleIds($addressQtys, $bundleIds)
    {
        $result = [];
        if (!is_array($bundleIds)) {
            $bundleIds = [$bundleIds];
        }
        foreach ($bundleIds as $bundleId) {
            $isValid = true;
            foreach ($addressQtys as $productId => $qty) {
                if (isset($this->qtyArrays[$productId][$bundleId])) {
                    $bundle_type = $this->bundlediscount->load($bundleId);
                    if ($this->qtyArrays[$productId][$bundleId] <= $qty ||
                        $bundle_type->getBundleOption() == "Flexible") {
                        $addressQtys[$productId] -= $this->qtyArrays[$productId][$bundleId];
                    } else {
                        $isValid = false;
                    }
                }
            }
            if ($isValid) {
                $result[] = $bundleId;
            }
        }
        return $result;
    }
    /**
     * Add discount total information to address.
     *
     * @param \Magento\Quote\Model\Quote               $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     *
     * @return array|null
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $result = null;

        $amount = $total->getDiscountAmount();
        if ($amount != 0) {
            $description = $total->getDiscountDescription();
            $result = [
                'code' => $this->getCode(),
                'title' => strlen($description) ? __('Discount (%1)', $description) : __('Discount'),
                'value' => $amount,
            ];
        }
        return $result;
    }
}
