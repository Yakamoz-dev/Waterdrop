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

interface BundleOptionsInterface
{

    /**
     * @api
     * @return array
     */
    public function createBundleOptions();

    /**
     * @api
     * @param int $bundleid
     * @return array
     */
    public function getBundleOptions($bundleid);

    /**
     * @api
     * @param int $bundleid
     * @return array
     */
    public function updateBundleOptions($bundleid);

    /**
     * @api
     * @param int $bundleid
     * @return array
     */
    public function deleteBundleOptions($bundleid);

    /**
     * @api
     * @param int $productid
     * @return array
     */
    public function getBundleByProduct($productid);

    /**
     * Retrieve bundle options matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magedelight\Bundlediscount\Api\Data\BundleOptionsSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );
}
