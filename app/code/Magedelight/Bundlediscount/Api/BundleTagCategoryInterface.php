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

namespace Magedelight\Bundlediscount\Api;

interface BundleTagCategoryInterface
{

    /**
     * @api
     * @return array
     */
    public function createBundleTagCategory();

    /**
     * @api
     * @param int $tagcategoryid
     * @return array
     */
    public function getBundleTagCategory($tagcategoryid);

    /**
     * @api
     * @param int $tagcategoryid
     * @return array
     */
    public function updateBundleTagCategory($tagcategoryid);

    /**
     * @api
     * @param int $tagcategoryid
     * @return array
     */
    public function deleteBundleTagCategory($tagcategoryid);

    /**
     * Retrieve category tag matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magedelight\Bundlediscount\Api\Data\BundleTagCategorySearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );
}
