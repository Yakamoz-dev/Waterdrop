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

namespace Magedelight\Bundlediscount\Api\Data;

interface BundleDiscountInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const BUNDLE_ID = 'bundle_id';
    const PRODUCT_ID = 'product_id';
    const NAME = 'name';
    const DISCOUNT_TYPE = 'discount_type';
    const DISCOUNT_PRICE = 'discount_price';
    const STATUS = 'status';
    const EXCLUDE_BASE_PRODUCT = 'exclude_base_product';
    const SORT_ORDER = 'sort_order';
    const STORE_IDS = 'store_ids';
    const CUSTOMER_GROUPS = 'customer_groups';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const QTY = 'qty';
    const DATE_FROM = 'date_from';
    const DATE_TO = 'date_to';
    const BUNDLE_OPTION = 'bundle_option';
    const BUNDLE_KEYWORDS = 'bundle_keywords';
    const BUNDLE_TAGS = 'bundle_tags';

    /**
     * Get bundle_id
     * @return int|null
     */
    public function getBundleId();

    /**
     * Set bundle_id
     * @param int $bundleId
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setStorelocatorId($bundleId);

    /**
     * @api
     * Get product_id
     * @return int|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param int $productId
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setProductId($productId);

    /**
     * Get bundle name
     * @return string|null
     */
    public function getName();

    /**
     * Set bundle name
     * @param string $bundleName
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setName($bundleName);

    /**
     * Get discount type
     * @return int|null
     */
    public function getDiscountType();

    /**
     * Set bundle name
     * @param int $discountType
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setDiscountType($discountType);

    /**
     * Get discount price
     * @return float|null
     */
    public function getDiscountPrice();

    /**
     * Set discount price
     * @param int $discountPrice
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setDiscountPrice($discountPrice);

    /**
     * Get status
     * @return boolean|null
     */
    public function getStatus();

    /**
     * Set status
     * @param boolean $status
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setStatus($status);

    /**
     * Get exclude base product
     * @return int|null
     */
    public function getExcludeBaseProduct();

    /**
     * Set exclude base product
     * @param int $excludeBaseProduct
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setExcludeBaseProduct($excludeBaseProduct);

    /**
     * Get sort order
     * @return int|null
     */
    public function getSortOrder();

    /**
     * Set sort order
     * @param int $sortOrder
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * Get store ids
     * @return string|null
     */
    public function getStoreIds();

    /**
     * Set store ids
     * @param string $storeIds
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setStoreIds($storeIds);

    /**
     * Get customer groups
     * @return string|null
     */
    public function getCustomerGroups();

    /**
     * Set customer groups
     * @param string $customerGroups
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setCustomerGroups($customerGroups);

    /**
     * Get created at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at
     * @param string $createdAt
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get created at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set created at
     * @param string $updatedAt
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get qty
     * @return int|null
     */
    public function getQty();

    /**
     * Set qty
     * @param int $qty
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setQty($qty);

    /**
     * Get date from
     * @return string|null
     */
    public function getDateFrom();

    /**
     * Set date from
     * @param string $datefrom
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setDateFrom($datefrom);

    /**
     * Get date to
     * @return string|null
     */
    public function getDateTo();

    /**
     * Set date to
     * @param string $dateto
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setDateTo($dateto);

    /**
     * Get bundle option
     * @return string|null
     */
    public function getBundleOption();

    /**
     * Set bundle option
     * @param string $bundleOption
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setBundleOption($bundleOption);

    /**
     * Get bundle keywords
     * @return string|null
     */
    public function getBundleKeywords();

    /**
     * Set bundle keywords
     * @param string $bundleKeywords
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setBundleKeywords($bundleKeywords);

    /**
     * Get bundle tags
     * @return string|null
     */
    public function getBundleTags();

    /**
     * Set bundle tags
     * @param string $bundleTags
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface
     */
    public function setBundleTags($bundleTags);
}
