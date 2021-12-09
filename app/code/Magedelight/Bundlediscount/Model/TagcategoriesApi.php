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

use Magedelight\Bundlediscount\Api\BundleTagCategoryInterface;
use Magedelight\Bundlediscount\Api\Data\BundleTagCategorySearchResultInterfaceFactory;
use Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;

class TagcategoriesApi implements BundleTagCategoryInterface
{

    /**
     * @var \Magento\Framework\Webapi\Rest\Request
     */
    protected $request;

    /**
     * @var \Magedelight\Bundlediscount\Model\TagcategoriesFactory
     */
    protected $mdTagCategoryFactory;

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory
     */
    protected $mdTagCatCollFactory;

    /**
     * @var BundleTagCategorySearchResultInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @param \Magento\Framework\Webapi\Rest\Request                                          $request
     * @param \Magedelight\Bundlediscount\Model\TagcategoriesFactory                          $mdTagCategoryFactory
     * @param \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $mdTagCatCollFactory
     * @param BundleTagCategorySearchResultInterfaceFactory                                   $searchResultsFactory
     */
    public function __construct(
        \Magento\Framework\Webapi\Rest\Request $request,
        \Magedelight\Bundlediscount\Model\TagcategoriesFactory $mdTagCategoryFactory,
        \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $mdTagCatCollFactory,
        BundleTagCategorySearchResultInterfaceFactory $searchResultsFactory
    ) {
        $this->request = $request;
        $this->mdTagCategoryFactory = $mdTagCategoryFactory;
        $this->mdTagCatCollFactory = $mdTagCatCollFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Create Tag Categories Records.
     *
     * @return array
     */
    public function createBundleTagCategory()
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
        $tagCategory = $this->mdTagCategoryFactory->create();
        if (isset($bodyParams['tag_categories'])) {
            if (isset($bodyParams['tag_categories']['name'])) {
                if (trim($bodyParams['tag_categories']['name']) == "") {
                    $returnArray = [
                        [
                            "message" => __('Please enter name field value.'),
                        ],
                    ];
                    return $returnArray;
                }
                $tagCategory->setData('name', $bodyParams['tag_categories']['name']);
            } else {
                $invalid = [
                    [
                        "message" => __('Fields are required. Please enter fields value in body'),
                    ],
                ];
                return $invalid;
            }
            if (isset($bodyParams['tag_categories']['is_active'])) {
                if ($bodyParams['tag_categories']['is_active'] != 1 && $bodyParams['tag_categories']['is_active'] != 2) {
                    $returnArray = [
                        [
                            "message" => __('Please enter value of status from 1 or 2. 1 is for Enabled and 2 is for Disabled.'),
                        ],
                    ];
                    return $returnArray;
                }
                $tagCategory->setData('is_active', $bodyParams['tag_categories']['is_active']);
            }
            $tagCategory->save();
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
     * Get Tag Categories Records by Tag Category ID.
     *
     * @param int $tagcategoryid
     * @return array
     */
    public function getBundleTagCategory($tagcategoryid)
    {
        $tagCategoryId = $this->mdTagCategoryFactory->create()->load($tagcategoryid);
        if ($tagCategoryId->getId()) {
            $returnArray = [
                [
                    "id" => $tagCategoryId->getId(),
                    "name" => $tagCategoryId->getName(),
                    "is_active" => $tagCategoryId->getIsActive(),
                    "created_at" => $tagCategoryId->getCreatedAt(),
                    "update_time" => $tagCategoryId->getUpdateTime(),
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

    /**
     * Update Tag Categories Records by Tag Category ID.
     *
     * @param int $tagcategoryid
     * @return array
     */
    public function updateBundleTagCategory($tagcategoryid)
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
        $tagCategory = $this->mdTagCategoryFactory->create()->load($tagcategoryid);
        if ($tagCategory->getId()) {
            if (isset($bodyParams['tag_categories'])) {
                if (isset($bodyParams['tag_categories']['name'])) {
                    if (trim($bodyParams['tag_categories']['name']) == "") {
                        $returnArray = [
                            [
                                "message" => __('Please enter name field value.'),
                            ],
                        ];
                        return $returnArray;
                    }
                    $tagCategory->setName($bodyParams['tag_categories']['name']);
                }
                if (isset($bodyParams['tag_categories']['is_active'])) {
                    if ($bodyParams['tag_categories']['is_active'] != 1 && $bodyParams['tag_categories']['is_active'] != 2) {
                        $returnArray = [
                            [
                                "message" => __('Please enter value of status from 1 or 2. 1 is for Enabled and 2 is for Disabled.'),
                            ],
                        ];
                        return $returnArray;
                    }
                    $tagCategory->setIsActive($bodyParams['tag_categories']['is_active']);
                }
                $tagCategory->save();
                $returnArray = [
                    [
                        "message" => __('Record Update Successfully'),
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
                    "message" => __('Record is not exist'),
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
    public function deleteBundleTagCategory($tagcategoryid)
    {
        $tagCategory = $this->mdTagCategoryFactory->create()->load($tagcategoryid);
        if ($tagCategory->getId()) {
            $tagCategory->delete();
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

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDataTagCategoryInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Magedelight\Bundlediscount\Api\Data\BundleDataTagCategoryInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\Collection $collection */
        $collection = $this->mdTagCatCollFactory->create();

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
            $field = 'entity_id';
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
