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
namespace Aheadworks\Sarp2\Model\Quote\Item\Grouping;

use Magento\Quote\Model\Quote\Item;

/**
 * Interface CriterionInterface
 * @package Aheadworks\Sarp2\Model\Quote\Item\Grouping
 */
interface CriterionInterface
{
    /**
     * Get grouping criterion value
     *
     * @param Item $quoteItem
     * @return string|null
     */
    public function getValue($quoteItem);

    /**
     * Get result item name
     *
     * @return string
     */
    public function getResultName();

    /**
     * Get grouping result value
     *
     * @param Item $quoteItem
     * @return mixed
     */
    public function getResultValue($quoteItem);
}
