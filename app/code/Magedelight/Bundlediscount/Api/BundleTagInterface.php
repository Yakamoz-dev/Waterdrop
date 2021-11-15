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

interface BundleTagInterface
{

    /**
     * @api
     * @return array
     */
    public function createBundleTag();

    /**
     * @api
     * @param int $tagid
     * @return array
     */
    public function getBundleTag($tagid);

    /**
     * @api
     * @param int $tagid
     * @return array
     */
    public function updateBundleTag($tagid);

    /**
     * @api
     * @param int $tagid
     * @return array
     */
    public function deleteBundleTag($tagid);

    /**
     * Retrieve category tag matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magedelight\Bundlediscount\Api\Data\BundleTagSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );
}
