<?php

/**
 * Magedelight
 * Copyright (C) 2019 Magedelight <info@magedelight.com>
 *
 * @category Magedelight
 * @package Magedelight_Bundlediscount
 * @copyright Copyright (c) 2019 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

namespace Magedelight\Bundlediscount\Model;

use Magedelight\Bundlediscount\Api\BundleOptionsInterface;
use Magedelight\Bundlediscount\Api\Data\BundleOptionsSearchResultInterfaceFactory;
use Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;

class BundleOptionsApi implements BundleOptionsInterface
{

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\CollectionFactory
     */
    protected $mdBundleDiscountCollObj;

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory
     */
    protected $mdBundleItemsCollObj;

    /**
     * @var \Magedelight\Bundlediscount\Model\BundleitemsFactory
     */
    protected $mdBundleItemsObj;

    /**
     * @var \Magedelight\Bundlediscount\Model\BundlediscountFactory
     */
    protected $mdBundleDiscountObj;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    protected $groupCollFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollFactory;

    /**
     * @var \Magento\Store\Model\StoreRepository
     */
    protected $storeRepo;

    /**
     * @var \Magento\Framework\Webapi\Rest\Request
     */
    protected $request;

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory
     */
    protected $mdTagWrapperCollFactory;

    /**
     * @var BundleOptionsSearchResultInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @param \Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\CollectionFactory $mdBundleDiscountCollObj
     * @param \Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory    $mdBundleItemsCollObj
     * @param \Magedelight\Bundlediscount\Model\BundlediscountFactory                          $mdBundleDiscountObj
     * @param \Magedelight\Bundlediscount\Model\BundleitemsFactory                             $mdBundleItemsObj
     * @param \Magento\Customer\Model\ResourceModel\Group\CollectionFactory                    $groupCollFactory
     * @param \Magento\Catalog\Model\ProductFactory                                            $productFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory                   $productCollFactory
     * @param \Magento\Store\Model\StoreRepository                                             $storeRepo
     * @param \Magento\Framework\Webapi\Rest\Request                                           $request
     * @param \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory     $mdTagWrapperCollFactory
     */
    public function __construct(
        \Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\CollectionFactory $mdBundleDiscountCollObj,
        \Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory $mdBundleItemsCollObj,
        \Magedelight\Bundlediscount\Model\BundlediscountFactory $mdBundleDiscountObj,
        \Magedelight\Bundlediscount\Model\BundleitemsFactory $mdBundleItemsObj,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $groupCollFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollFactory,
        \Magento\Store\Model\StoreRepository $storeRepo,
        \Magento\Framework\Webapi\Rest\Request $request,
        \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory $mdTagWrapperCollFactory,
        BundleOptionsSearchResultInterfaceFactory $searchResultsFactory
    ) {
        $this->mdBundleDiscountCollObj = $mdBundleDiscountCollObj;
        $this->mdBundleItemsCollObj = $mdBundleItemsCollObj;
        $this->mdBundleDiscountObj = $mdBundleDiscountObj;
        $this->mdBundleItemsObj = $mdBundleItemsObj;
        $this->groupCollFactory = $groupCollFactory;
        $this->productFactory = $productFactory;
        $this->productCollFactory = $productCollFactory;
        $this->storeRepo = $storeRepo;
        $this->request = $request;
        $this->mdTagWrapperCollFactory = $mdTagWrapperCollFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Create Tag Categories Records.
     *
     * @return array
     */
    public function createBundleOptions()
    {
        $bodyParams = $this->request->getBodyParams();
        if (!$bodyParams) {
            $invalid = [
                [
                    "message" => __('Fields are required. Please enter fields value in body'),
                ],
            ];
            return $invalid;
        }
        $mdBundleDiscObj = $this->mdBundleDiscountObj->create();

        if (isset($bodyParams['bundle_options'])) {
            if (!isset($bodyParams['bundle_options']['product_name'])) {
                return [
                    [
                        "message" => __('Please enter product name.'),
                    ],
                ];
            }
            $productColl = $this->productCollFactory->create()->addFieldToSelect('*')
                ->addFieldToFilter('name', ['eq' => $bodyParams['bundle_options']['product_name']])
                ->getLastItem();
            if (!$productColl->getId()) {
                return [
                    [
                        "message" => __('Invalid Product Name.'),
                    ],
                ];
            }
            if (isset($bodyParams['bundle_options']['product_name'])) {
                $mdBundleDiscObj->setData('product_id', $productColl->getId());
            }
            if (isset($bodyParams['bundle_options']['name'])) {
                $mdBundleDiscObj->setData('name', $bodyParams['bundle_options']['name']);
            }if (isset($bodyParams['bundle_options']['status'])) {
                if ($bodyParams['bundle_options']['status'] != 1 && $bodyParams['bundle_options']['status'] != 2) {
                    $returnArray = [
                        [
                            "message" => __('Please enter value of status from 1 or 2. 1 is for Enabled and 2 is for Disabled.'),
                        ],
                    ];
                    return $returnArray;
                }
                $mdBundleDiscObj->setData('status', $bodyParams['bundle_options']['status']);
            }if (isset($bodyParams['bundle_options']['date_from'])) {
                $mdBundleDiscObj->setData('date_from', $bodyParams['bundle_options']['date_from']);
            }if (isset($bodyParams['bundle_options']['date_to'])) {
                $mdBundleDiscObj->setData('date_to', $bodyParams['bundle_options']['date_to']);
            }if (isset($bodyParams['bundle_options']['bundle_option'])) {
                $mdBundleDiscObj->setData('bundle_option', $bodyParams['bundle_options']['bundle_option']);
            }if (isset($bodyParams['bundle_options']['discount_type'])) {
                $mdBundleDiscObj->setData('discount_type', $bodyParams['bundle_options']['discount_type']);
            }

            if (isset($bodyParams['bundle_options']['discount_price'])) {
                $mdBundleDiscObj->setData('discount_price', $bodyParams['bundle_options']['discount_price']);
            } else {
                $returnArray = [
                    [
                        "message" => __('Please enter value of discount price.'),
                    ],
                ];
                return $returnArray;
            }

            if (isset($bodyParams['bundle_options']['exclude_base_product'])) {
                $mdBundleDiscObj->setData('exclude_base_product', $bodyParams['bundle_options']['exclude_base_product']);
            }if (isset($bodyParams['bundle_options']['bundle_keywords'])) {
                $mdBundleDiscObj->setData('bundle_keywords', $bodyParams['bundle_options']['bundle_keywords']);
            }if (isset($bodyParams['bundle_options']['bundle_tags'])) {
                $tags = $bodyParams['bundle_options']['bundle_tags'];
                $tagWrapperObj = $this->mdTagWrapperCollFactory->create()->addFieldToSelect('*')->addFieldToFilter('name', ['in' => explode(",", $tags)]);

                $bundle_tags_arr = [];
                foreach ($tagWrapperObj as $key => $value) {
                    $bundle_tags_arr[] = $value->getId();
                }

                if (count($bundle_tags_arr) > 0) {
                    $bundle_tags = implode(",", $bundle_tags_arr);
                } else {
                    $returnArray = [
                        [
                            "message" => __($tags . ' tag is invalid.'),
                        ],
                    ];
                    return $returnArray;
                }
                $mdBundleDiscObj->setData('bundle_tags', $bundle_tags);
            }if (isset($bodyParams['bundle_options']['sort_order'])) {
                $mdBundleDiscObj->setData('sort_order', $bodyParams['bundle_options']['sort_order']);
            }if (isset($bodyParams['bundle_options']['customer_groups'])) {
                $custGrpArr = explode(",", $bodyParams['bundle_options']['customer_groups']);
                $groupColl = $this->groupCollFactory->create()->loadData()->toOptionArray();
                $grpCode = [];
                foreach ($custGrpArr as $grpKey => $grpValue) {
                    foreach ($groupColl as $key => $value) {
                        if (trim($grpValue) == $value['label']) {
                            $grpCode[] = $value['value'];
                            break;
                        }
                    }
                }
                if (count($grpCode) > 0) {
                    $mdBundleDiscObj->setData('customer_groups', implode(",", $grpCode));
                }
            }if (isset($bodyParams['bundle_options']['store_ids'])) {
                $storeList = $this->getStoreList();
                $storeArr = [];
                $storeIds = explode(",", $bodyParams['bundle_options']['store_ids']);
                foreach ($storeIds as $storeKey => $storeValue) {
                    foreach ($storeList as $key => $value) {
                        if (trim($storeValue) == $value) {
                            $storeArr[] = $key;
                            break;
                        }
                    }
                }
                if (count($storeArr) > 0) {
                    $mdBundleDiscObj->setData('store_ids', implode(",", $storeArr));
                }
            }
            $mdBundleDiscObj->save();
            if (isset($bodyParams['bundle_options']['products_collection'])) {
                if (count($bodyParams['bundle_options']['products_collection']) > 0) {
                    foreach ($bodyParams['bundle_options']['products_collection'] as $key => $value) {
                        if ($value['name'] != $productColl->getName()) {
                            $mdBundleItemsObj = $this->mdBundleItemsObj->create();
                            $productColl = $this->productCollFactory->create()->addFieldToSelect('*')->addFieldToFilter('name', ['eq' => $value['name']])->getLastItem();
                            $mdBundleItemsObj->setData('bundle_id', $mdBundleDiscObj->getId());
                            if (!$productColl->getId()) {
                                $returnArray = [
                                    [
                                        "message" => __($value['name'] . ' product name is invalid'),
                                    ],
                                ];
                                return $returnArray;
                            }
                            $mdBundleItemsObj->setData('product_id', $productColl->getId());
                            $mdBundleItemsObj->setData('qty', $value['qty']);
                            $mdBundleItemsObj->setData('sort_order', $value['sort_order']);
                            $mdBundleItemsObj->save();
                        } else {
                            $returnArray = [
                                [
                                    "message" => __($value['name'] . ' same product name is invalid'),
                                ],
                            ];
                            return $returnArray;
                        }
                    }
                }
            }
            $returnArray = [
                [
                    "message" => __('Record Created Successfully'),
                ],
            ];
        } else {
            $returnArray = [
                [
                    "message" => __('Format is not valid'),
                ],
            ];
        }
        return $returnArray;
    }

    /**
     * Get Bundle Options by Bundle Options ID.
     *
     * @param int $bundleOptionsId
     * @return array
     */
    public function getBundleOptions($bundleOptionsId)
    {
        $bundleOptObj = $this->mdBundleDiscountObj->create()->load($bundleOptionsId);
        if ($bundleOptObj->getId()) {
            $bundleDiscObj = $this->mdBundleDiscountCollObj->create()->addFieldToSelect('*')->addFieldToFilter('bundle_id', ['eq' => $bundleOptionsId])->getLastItem();
            $bundleItemObj = $this->mdBundleItemsCollObj->create()->addFieldToSelect('*')->addFieldToFilter('bundle_id', ['eq' => $bundleDiscObj->getId()]);
            $product = $this->productFactory->create();
            $returnArray = [];
            $returnArray['items'] = [
                'name' => $bundleDiscObj->getName(),
                'status' => $bundleDiscObj->getStatus(),
                'date_from' => $bundleDiscObj->getDateFrom(),
                'date_to' => $bundleDiscObj->getDateTo(),
                'bundle_option' => $bundleDiscObj->getBundleOption(),
                'discount_type' => $bundleDiscObj->getDiscountType(),
                'discount_price' => $bundleDiscObj->getDiscountPrice(),
                'exclude_base_product' => $bundleDiscObj->getExcludeBaseProduct(),
                'bundle_keywords' => $bundleDiscObj->getBundleKeywords(),
                'bundle_tags' => $bundleDiscObj->getBundleTags(),
                'sort_order' => $bundleDiscObj->getSortOrder(),
                'customer_groups' => $bundleDiscObj->getCustomerGroups(),
                'store_ids' => $bundleDiscObj->getStoreIds(),
            ];
            if ($bundleItemObj->getSize() > 0) {
                foreach ($bundleItemObj as $key => $value) {
                    $productObj = $product->load($value->getProductId());
                    $returnArray['products'][$key]['bundle_id'] = $value->getBundleId();
                    $returnArray['products'][$key]['name'] = $productObj->getName();
                    $returnArray['products'][$key]['qty'] = $value->getQty();
                    $returnArray['products'][$key]['sort_order'] = $value->getSortOrder();
                }
            }
        } else {
            $returnArray = [
                [
                    "message" => __('Record is not exist'),
                ],
            ];
        }
        return $returnArray;
    }

    /**
     * Update Bundle Options by .
     *
     * @param int $bundleOptionsId
     * @return array
     */
    public function updateBundleOptions($bundleOptionsId)
    {
        $bodyParams = $this->request->getBodyParams();
        if (!$bodyParams) {
            $invalid = [
                [
                    "message" => __('Fields are required. Please enter fields value in body'),
                ],
            ];
            return $invalid;
        }
        $mdBundleDiscObj = $this->mdBundleDiscountObj->create()->load($bundleOptionsId);
        if ($mdBundleDiscObj->getId()) {
            if (isset($bodyParams['bundle_options'])) {
                if (isset($bodyParams['bundle_options']['product_name'])) {
                    $productColl = $this->productCollFactory->create()->addFieldToSelect('*')
                        ->addFieldToFilter('name', ['eq' => $bodyParams['bundle_options']['product_name']])
                        ->getLastItem();
                    if (!$productColl->getId()) {
                        return [
                            [
                                "message" => __('Invalid Product Name.'),
                            ],
                        ];
                    }
                    $mdBundleDiscObj->setProductId($productColl->getId());
                }
                if (isset($bodyParams['bundle_options']['name'])) {
                    $mdBundleDiscObj->setName($bodyParams['bundle_options']['name']);
                }if (isset($bodyParams['bundle_options']['status'])) {
                    if ($bodyParams['bundle_options']['status'] != 1 && $bodyParams['bundle_options']['status'] != 2) {
                        $returnArray = [
                            [
                                "message" => __('Please enter value of status from 1 or 2. 1 is for Enabled and 2 is for Disabled.'),
                            ],
                        ];
                        return $returnArray;
                    }
                    $mdBundleDiscObj->setStatus($bodyParams['bundle_options']['status']);
                }if (isset($bodyParams['bundle_options']['date_from'])) {
                    $mdBundleDiscObj->setDateFrom($bodyParams['bundle_options']['date_from']);
                }if (isset($bodyParams['bundle_options']['date_to'])) {
                    $mdBundleDiscObj->setDateTo($bodyParams['bundle_options']['date_to']);
                }if (isset($bodyParams['bundle_options']['bundle_option'])) {
                    $mdBundleDiscObj->setBundleOption($bodyParams['bundle_options']['bundle_option']);
                }if (isset($bodyParams['bundle_options']['discount_type'])) {
                    $mdBundleDiscObj->setDiscountType($bodyParams['bundle_options']['discount_type']);
                }
                if (isset($bodyParams['bundle_options']['discount_price'])) {
                    $mdBundleDiscObj->setDiscountPrice($bodyParams['bundle_options']['discount_price']);
                }
                if (isset($bodyParams['bundle_options']['exclude_base_product'])) {
                    $mdBundleDiscObj->setExcludeBaseProduct($bodyParams['bundle_options']['exclude_base_product']);
                }if (isset($bodyParams['bundle_options']['bundle_keywords'])) {
                    $mdBundleDiscObj->setBundleKeywords($bodyParams['bundle_options']['bundle_keywords']);
                }if (isset($bodyParams['bundle_options']['bundle_tags'])) {
                    $tags = $bodyParams['bundle_options']['bundle_tags'];
                    $tagWrapperObj = $this->mdTagWrapperCollFactory->create()->addFieldToSelect('*')->addFieldToFilter('name', ['in' => explode(",", $tags)]);

                    $bundle_tags_arr = [];
                    foreach ($tagWrapperObj as $key => $value) {
                        $bundle_tags_arr[] = $value->getId();
                    }

                    if (count($bundle_tags_arr) > 0) {
                        $bundle_tags = implode(",", $bundle_tags_arr);
                    } else {
                        $returnArray = [
                            [
                                "message" => __($tags . ' tag is invalid.'),
                            ],
                        ];
                        return $returnArray;
                    }
                    $mdBundleDiscObj->setBundleTags($bundle_tags);
                }if (isset($bodyParams['bundle_options']['sort_order'])) {
                    $mdBundleDiscObj->setSortOrder($bodyParams['bundle_options']['sort_order']);
                }if (isset($bodyParams['bundle_options']['customer_groups'])) {
                    $custGrpArr = explode(",", $bodyParams['bundle_options']['customer_groups']);
                    $groupColl = $this->groupCollFactory->create()->loadData()->toOptionArray();
                    $grpCode = [];
                    foreach ($custGrpArr as $grpKey => $grpValue) {
                        foreach ($groupColl as $key => $value) {
                            if (trim($grpValue) == $value['label']) {
                                $grpCode[] = $value['value'];
                                break;
                            }
                        }
                    }
                    if (count($grpCode) > 0) {
                        $mdBundleDiscObj->setCustomerGroups(implode(",", $grpCode));
                    }
                }if (isset($bodyParams['bundle_options']['store_ids'])) {
                    $storeList = $this->getStoreList();
                    $storeArr = [];
                    $storeIds = explode(",", $bodyParams['bundle_options']['store_ids']);
                    foreach ($storeIds as $storeKey => $storeValue) {
                        foreach ($storeList as $key => $value) {
                            if (trim($storeValue) == $value) {
                                $storeArr[] = $key;
                                break;
                            }
                        }
                    }
                    if (count($storeArr) > 0) {
                        $mdBundleDiscObj->setStoreIds(implode(",", $storeArr));
                    }
                }
                $mdBundleDiscObj->save();
                if (isset($bodyParams['bundle_options']['product_collection'])) {
                    if (count($bodyParams['bundle_options']['product_collection']) > 0) {
                        foreach ($bodyParams['bundle_options']['product_collection'] as $key => $value) {
                            if ($value['name'] != $productColl->getName()) {
                                $mdBundleItemsObj = $this->mdBundleItemsObj->create();
                                $productColl = $this->productCollFactory->create()->addFieldToSelect('*')->addFieldToFilter('name', ['eq' => $value['name']])->getLastItem();
                                $mdBundleItemsObj->setData('bundle_id', $mdBundleDiscObj->getId());
                                if (!$productColl->getId()) {
                                    $returnArray = [
                                        [
                                            "message" => __($value['name'] . ' product name is invalid'),
                                        ],
                                    ];
                                    return $returnArray;
                                }
                                $mdBundleItemsObj->setProductId($productColl->getId());
                                $mdBundleItemsObj->setQty($value['qty']);
                                $mdBundleItemsObj->setSortOrder($value['sort_order']);
                                $mdBundleItemsObj->save();
                            } else {
                                $returnArray = [
                                    [
                                        "message" => __($value['name'] . ' same product name is invalid'),
                                    ],
                                ];
                                return $returnArray;
                            }
                        }
                    }
                }
                $returnArray = [
                    [
                        "message" => __('Record Updated Successfully'),
                    ],
                ];
            } else {
                $returnArray = [
                    [
                        "message" => __('Format is not valid'),
                    ],
                ];
            }
        } else {
            $returnArray = [
                [
                    "message" => __('Record is not exist.'),
                ],
            ];
        }
        return $returnArray;
    }

    /**
     * Delete Tag Categories Records by Tag Category ID.
     *
     * @param int $tagcategoryid
     * @return array
     */
    public function deleteBundleOptions($bundleOptionsId)
    {
        $mdBundleDiscount = $this->mdBundleDiscountObj->create()->load($bundleOptionsId);
        if ($mdBundleDiscount->getId()) {
            $mdBundleDiscountItem = $this->mdBundleItemsCollObj->create()->addFieldToSelect('*')->addFieldToFilter('bundle_id', ['eq' => $bundleOptionsId]);
            if ($mdBundleDiscountItem->getSize() > 0) {
                foreach ($mdBundleDiscountItem as $key => $value) {
                    $mdBundleOptObj = $this->mdBundleItemsObj->create()->load($value->getId());
                    $mdBundleOptObj->delete();
                }
            }
            $mdBundleDiscount->delete();
            $returnArray = [
                [
                    "message" => __('Record deleted Successfully'),
                ],
            ];
        } else {
            $returnArray = [
                [
                    "message" => __('Record is not exist'),
                ],
            ];
        }
        return $returnArray;
    }

    private function getStoreList()
    {
        $storeListColl = $this->storeRepo->getList();
        $websiteIds = [];
        $storeList = [];
        foreach ($storeListColl as $store) {
            $websiteId = $store["website_id"];
            $storeId = $store["store_id"];
            $storeName = $store["name"];
            $storeList[$storeId] = $storeName;
            array_push($websiteIds, $websiteId);
        }
        return $storeList;
    }

    /**
     * Get Bundle By Product ID.
     *
     * @param int $productid
     * @return array
     */
    public function getBundleByProduct($productid)
    {
        $bundleDiscObj = $this->mdBundleDiscountCollObj->create()->addFieldToSelect('*')->addFieldToFilter('product_id', ['eq' => $productid]);
        $product = $this->productFactory->create();
        if ($bundleDiscObj->getSize() > 0) {
            foreach ($bundleDiscObj as $bundleKey => $bundleValue) {
                $bundleItemObj = $this->mdBundleItemsCollObj->create()->addFieldToSelect('*')->addFieldToFilter('bundle_id', ['eq' => $bundleValue->getId()]);
                $bundleDiscObjData['items']['bundle_id'] = $bundleValue->getId();
                $bundleDiscObjData['items']['name'] = $bundleValue->getName();
                $bundleDiscObjData['items']['discount_price'] = $bundleValue->getDiscountPrice();
                $bundleDiscObjData['items']['status'] = $bundleValue->getStatus();

                if ($bundleItemObj->getSize() > 0) {
                    foreach ($bundleItemObj as $key => $value) {
                        $productObj = $product->load($value->getProductId());
                        $bundleDiscObjData['items']['product_items'][$key]['bundle_id'] = $value->getBundleId();
                        $bundleDiscObjData['items']['product_items'][$key]['name'] = $productObj->getName();
                        $bundleDiscObjData['items']['product_items'][$key]['qty'] = $value->getQty();
                        $bundleDiscObjData['items']['product_items'][$key]['sort_order'] = $value->getSortOrder();
                    }
                }
            }
            return $bundleDiscObjData;
        } else {
            return [
                [
                    "message" => __('Record is not exist.'),
                ],
            ];
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magedelight\Bundlediscount\Api\Data\BundleOptionsSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Magedelight\Bundlediscount\Api\Data\BundleOptionsSearchResultInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\Collection $collection */
        $collection = $this->mdBundleDiscountCollObj->create();

        //Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        /** @var SortOrder $sortOrder */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            // set a default sorting order since this method is used constantly in many
            // different blocks
            $field = 'bundle_id';
            $collection->addOrder($field, 'ASC');
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($collection->getItems());
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection $collection
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     */
    protected function addFilterGroupToCollection(FilterGroup $filterGroup, Collection $collection)
    {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = [$condition => $filter->getValue()];
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
        return $this;
    }
}
