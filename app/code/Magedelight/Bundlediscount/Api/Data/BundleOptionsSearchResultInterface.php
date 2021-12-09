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

interface BundleOptionsSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Bundle Options list.
     * @return \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface[]
     */
    public function getItems();

    /**
     * Set Bundle Options list.
     * @param \Magedelight\Bundlediscount\Api\Data\BundleDiscountInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
