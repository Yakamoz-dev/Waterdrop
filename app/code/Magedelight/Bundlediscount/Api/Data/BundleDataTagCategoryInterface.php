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

interface BundleDataTagCategoryInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const TAG_CATEGORY_ID = 'entity_id';
    const NAME = 'name';
    const IS_ACTIVE = 'is_active';
    const CREATED_AT = 'created_at';
    const UPDATE_TIME = 'update_time';

    /**
     * Get tag category id
     * @return int|null
     */
    public function getId();

    /**
     * Set tag category id
     * @param int $tagCategoryId
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDataTagCategoryInterface
     */
    public function setId($tagCategoryId);

    /**
     * Get bundle tag category name
     * @return string|null
     */
    public function getName();

    /**
     * Set bundle tag category name
     * @param string $tagCategoryName
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDataTagCategoryInterface
     */
    public function setName($tagCategoryName);

    /**
     * Get status
     * @return boolean|null
     */
    public function getIsActive();

    /**
     * Set status
     * @param boolean $isActive
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDataTagCategoryInterface
     */
    public function setIsActive($isActive);

    /**
     * Get created at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at
     * @param string $createdAt
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDataTagCategoryInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get update time
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Set update time
     * @param string $updateTime
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDataTagCategoryInterface
     */
    public function setUpdateTime($updateTime);
}
