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

use Magedelight\Bundlediscount\Api\BundleTagInterface;
use Magedelight\Bundlediscount\Api\Data\BundleTagSearchResultInterfaceFactory;
use Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;

class TagwrapperApi implements BundleTagInterface
{

    /**
     * @var \Magento\Framework\Webapi\Rest\Request
     */
    protected $request;

    /**
     * @var \Magedelight\Bundlediscount\Model\TagwrapperFactory
     */
    protected $mdTagWrapperFactory;

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory
     */
    protected $mdTagCategoryFactory;

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory
     */
    protected $mdTagCollFactory;

    /**
     * @var BundleTagSearchResultInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @param \Magento\Framework\Webapi\Rest\Request                                          $request
     * @param \Magedelight\Bundlediscount\Model\TagwrapperFactory                             $mdTagWrapperFactory
     * @param \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $mdTagCategoryFactory
     * @param \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory    $mdTagCollFactory
     * @param BundleTagSearchResultInterfaceFactory                                           $searchResultsFactory
     */
    public function __construct(
        \Magento\Framework\Webapi\Rest\Request $request,
        \Magedelight\Bundlediscount\Model\TagwrapperFactory $mdTagWrapperFactory,
        \Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory $mdTagCategoryFactory,
        \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory $mdTagCollFactory,
        BundleTagSearchResultInterfaceFactory $searchResultsFactory
    ) {
        $this->request = $request;
        $this->mdTagWrapperFactory = $mdTagWrapperFactory;
        $this->mdTagCategoryFactory = $mdTagCategoryFactory;
        $this->mdTagCollFactory = $mdTagCollFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Create Tag.
     *
     * @return array
     */
    public function createBundleTag()
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
        $tagWrapper = $this->mdTagWrapperFactory->create();
        if (isset($bodyParams['tag'])) {
            if (isset($bodyParams['tag']['category'])) {
                $tagCategory = $this->mdTagCategoryFactory->create()
                    ->addFieldToSelect('*')
                    ->addFieldToFilter('name', ['eq' => $bodyParams['tag']['category']])
                    ->getLastItem();
                if ($tagCategory->getId()) {
                    $tagWrapper->setData('category', $tagCategory->getId());
                } else {
                    $returnArray = [
                        [
                            "message" => __($bodyParams['tag']['category'] . ' Tag Category is not exist.'),
                        ],
                    ];
                    return $returnArray;
                }
            }
            if (isset($bodyParams['tag']['name'])) {
                if (trim($bodyParams['tag']['name']) == "") {
                    $returnArray = [
                        [
                            "message" => __('Please enter name field value.'),
                        ],
                    ];
                    return $returnArray;
                }
                $tagWrapper->setData('name', $bodyParams['tag']['name']);
            }
            if (isset($bodyParams['tag']['is_active'])) {
                if ($bodyParams['tag']['is_active'] != 1 && $bodyParams['tag']['is_active'] != 2) {
                    $returnArray = [
                        [
                            "message" => __('Please enter value of status from 1 or 2. 1 is for Enabled and 2 is for Disabled.'),
                        ],
                    ];
                    return $returnArray;
                }
                $tagWrapper->setData('is_active', $bodyParams['tag']['is_active']);
            }
            $tagWrapper->save();
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
     * Get Tag Records by Tag ID.
     *
     * @param int $tagid
     * @return array
     */
    public function getBundleTag($tagid)
    {
        $tagWrapper = $this->mdTagWrapperFactory->create()->load($tagid);
        if ($tagWrapper->getId()) {
            $returnArray = [
                [
                    "id" => $tagWrapper->getId(),
                    "category" => $tagWrapper->getCategory(),
                    "name" => $tagWrapper->getName(),
                    "is_active" => $tagWrapper->getIsActive(),
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
     * Update Tag by Tag ID.
     *
     * @param int $tagid
     * @return array
     */
    public function updateBundleTag($tagid)
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
        $tagWrapper = $this->mdTagWrapperFactory->create()->load($tagid);
        if ($tagWrapper->getId()) {
            if (isset($bodyParams['tag'])) {
                if (isset($bodyParams['tag']['category'])) {
                    $tagCategory = $this->mdTagCategoryFactory->create()
                        ->addFieldToSelect('*')
                        ->addFieldToFilter('name', ['eq' => $bodyParams['tag']['category']])
                        ->getLastItem();
                    if ($tagCategory->getId()) {
                        $tagWrapper->setCategory($tagCategory->getId());
                    } else {
                        $returnArray = [
                            [
                                "message" => __($bodyParams['tag']['category'] . ' Tag Category is not exist.'),
                            ],
                        ];
                        return $returnArray;
                    }
                }
                if (isset($bodyParams['tag']['name'])) {
                    if (trim($bodyParams['tag']['name']) == "") {
                        $returnArray = [
                            [
                                "message" => __('Please enter name field value.'),
                            ],
                        ];
                        return $returnArray;
                    }
                    $tagWrapper->setName($bodyParams['tag']['name']);
                }
                if (isset($bodyParams['tag']['is_active'])) {
                    if (($bodyParams['tag']['is_active'] != 1) && ($bodyParams['tag']['is_active'] != 2)) {
                        $returnArray = [
                            [
                                "message" => __('Please enter value of status from 1 or 2. 1 is for Enabled and 2 is for Disabled.'),
                            ],
                        ];
                        return $returnArray;
                    }
                    $tagWrapper->setIsActive($bodyParams['tag']['is_active']);
                }
                $tagWrapper->save();
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
     * Delete Tag Records by Tag ID.
     *
     * @param int $tagid
     * @return array
     */
    public function deleteBundleTag($tagid)
    {
        $tagWrapper = $this->mdTagWrapperFactory->create()->load($tagid);
        if ($tagWrapper->getId()) {
            $tagWrapper->delete();
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
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDataTagInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Magedelight\Bundlediscount\Api\Data\BundleDataTagInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\Collection $collection */
        $collection = $this->mdTagCollFactory->create();

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
            $field = 'id';
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
    }
}
