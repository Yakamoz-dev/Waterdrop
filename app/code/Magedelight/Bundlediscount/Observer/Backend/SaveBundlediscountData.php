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

namespace Magedelight\Bundlediscount\Observer\Backend;

use Magento\Framework\Event\ObserverInterface;

class SaveBundlediscountData implements ObserverInterface
{
    protected $request;
    private $messageManager;

    /**
     * SaveBundlediscountData constructor.
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magedelight\Bundlediscount\Model\BundlediscountFactory $bundlediscountFactory
     * @param \Magedelight\Bundlediscount\Model\BundleitemsFactory $bundleitemsFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magedelight\Bundlediscount\Model\BundlediscountFactory $bundlediscountFactory,
        \Magedelight\Bundlediscount\Model\BundleitemsFactory $bundleitemsFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->bundlediscount = $bundlediscountFactory;
        $this->bundleItem = $bundleitemsFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return bool
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        $postOption = $this->request->getPostValue();

        if (isset($postOption['bundlediscount_options'])) {
            $options = $postOption['bundlediscount_options'];
            if (isset($postOption['bundlediscount_selections'])) {
                $selections = $postOption['bundlediscount_selections'];
            } else {
                $this->messageManager->addError(__('Please add bundle products to save bundle'));
                return;
            }

            if (is_array($options) && count($options) > 0) {
                foreach ($options as $i => $option) {
                    if ($option['delete'] != 1) {
                        $bundleModel = $this->bundlediscount->create();
                        $bundleModel->setData('discount_type', $option['discount_type'])
                                ->setData('name', $option['name'])
                                ->setData('discount_price', $option['discount_price'])
                                ->setData('status', $option['status'])
                                ->setData('exclude_base_product', $option['exclude_base_product'])
                                ->setData('customer_groups', $option['customer_groups'])
                                ->setData('store_ids', $option['store_ids'])
                                ->setData('sort_order', $option['sort_order'])
                                ->setData('product_id', $product->getId())
                                ->setData('date_from', (isset($option['date_from']) && $option['date_from'] != '') ?
                                    $option['date_from'] : null)
                                ->setData('date_to', (isset($option['date_to']) && $option['date_to'] != '') ?
                                    $option['date_to'] : null)
                                ->setData('qty', $option['qty'])
                                ->setData('bundle_option', $option['bundle_option'])
                                ->setData('bundle_keywords', $option['bundle_keywords'])
                                ->setData('bundle_tags', $option['bundle_tags']);

                        try {
                            if ($option['bundle_id'] != null || $option['bundle_id'] != '') {
                                $bundleModel->setId($option['bundle_id']);
                            } else {
                                $bundleModel->setData('created_at', date('Y-m-d H:i:s'));
                            }
                            $bundleModel->save();
                            $bundleId = $bundleModel->getId();
                            if (isset($selections[$i]) && is_array($selections[$i]) && count($selections[$i]) > 0) {
                                foreach ($selections[$i] as $item) {
                                    if ($item['delete'] != 1) {
                                        $itemsModel = $this->bundleItem->create();
                                        $itemsModel->setData('bundle_id', $bundleId)
                                                ->setData('product_id', $item['product_id'])
                                                ->setData('qty', $item['qty'])
                                                ->setData('sort_order', $item['sort_order']);
                                        try {
                                            if ($item['item_id'] != null || $item['item_id'] != '') {
                                                $itemsModel->setId($item['item_id']);
                                            }
                                            $itemsModel->save();
                                        } catch (\Exception $e) {
                                            $this->messageManager->addError($e->getMessage());
                                        }
                                    } else {
                                        $itemsModel = $this->bundleItem->create()->setId($item['item_id']);
                                        try {
                                            $itemsModel->delete();
                                        } catch (\Exception $e) {
                                            $errorMessage = "Error while deleting product bundle items";
                                            $this->messageManager->addError(__($errorMessage));
                                        }
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            $this->messageManager->addError($e->getMessage());
                        }
                    } else {
                        $bundleModel = $this->bundlediscount->create()->setId($option['bundle_id']);
                        try {
                            $bundleModel->delete();
                        } catch (\Exception $e) {
                            $this->messageManager->addError(__('Error while deleting product bundle items'));
                        }
                    }
                }
            }
        }
    }
}
