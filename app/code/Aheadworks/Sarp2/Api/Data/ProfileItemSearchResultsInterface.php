<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Sarp2
 * @version    2.15.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface ProfileItemSearchResultsInterface
 *
 * @package Aheadworks\Sarp2\Api\Data
 */
interface ProfileItemSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get profile item list
     *
     * @return \Aheadworks\Sarp2\Api\Data\ProfileItemInterface[]
     */
    public function getItems();

    /**
     * Set profile item list
     *
     * @param \Aheadworks\Sarp2\Api\Data\ProfileItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
