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

namespace Magedelight\Bundlediscount\Observer;

use Magento\Framework\Event\ObserverInterface;

class ValidateBundleIds implements ObserverInterface
{
    /**
     * ValidateBundleIds constructor.
     * @param \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount
     */
    public function __construct(
        \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount
    ) {
        $this->bundlediscount = $bundlediscount;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return bool
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        if ($quote->getId()) {
            $bundleIds = explode(',', $quote->getData('bundle_ids'));
            if ($bundleIds[0] == '') {
                unset($bundleIds[0]);
            }

            if (count($bundleIds) > 0) {
                $qtyArrays = $this->bundlediscount->calculateProductQtys($bundleIds);
                $quoteQtys = $this->_calculateQuoteItemsQtys($quote, $qtyArrays);

                $quoteQtyskey = array_keys($quoteQtys, 0);
                
                $result = [];
                foreach ($bundleIds as $bundleId) {
                    $isValid = true;
                    foreach ($quoteQtys as $productId => $qty) {
                        if (isset($qtyArrays[$productId][$bundleId])) {
                            if ($qtyArrays[$productId][$bundleId] <= $qty) {

                                if (is_array($quoteQtyskey)) {
                                    foreach ($quoteQtyskey as $qkey) {
                                        $quoteQtys[$qkey] = 0;
                                        $qtyArrays[$qkey][$bundleId] = 0;
                                    }
                                } else {
                                    $quoteQtys[$quoteQtyskey] = 0;
                                    $qtyArrays[$quoteQtyskey][$bundleId] = 0;
                                }
                            } else {
                                $isValid = false;
                            }
                        }
                    }
                    if ($isValid) {
                        $result[] = $bundleId;
                    }
                }
                
                if (empty($result)) {
                    $idString = '';
                } elseif (count($result) == 1) {
                    $idString = $result[0];
                } else {
                    $idString = implode(',', $result);
                }


                $quote->setData('bundle_ids', $idString);
            }
        }
        return $this;
    }

    /**
     * @param $quote
     * @param $qtys
     * @return array
     */
    private function _calculateQuoteItemsQtys($quote, $qtys)
    {
        $result = [];
        $keys = array_keys($qtys);
        foreach ($quote->getAllItems() as $item) {
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
}
