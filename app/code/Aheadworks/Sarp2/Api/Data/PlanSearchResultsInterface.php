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
 * @version    2.15.3
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface PlanSearchResultsInterface
 * @package Aheadworks\Sarp2\Api\Data
 */
interface PlanSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get plans list
     *
     * @return \Aheadworks\Sarp2\Api\Data\PlanInterface[]
     */
    public function getItems();

    /**
     * Set plans list
     *
     * @param \Aheadworks\Sarp2\Api\Data\PlanInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
