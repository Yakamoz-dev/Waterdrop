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
 * Interface PaymentTokenSearchResultsInterface
 * @package Aheadworks\Sarp2\Api\Data
 */
interface PaymentTokenSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get payment tokens list
     *
     * @return \Aheadworks\Sarp2\Api\Data\PaymentTokenInterface[]
     */
    public function getItems();

    /**
     * Set payment tokens list
     *
     * @param \Aheadworks\Sarp2\Api\Data\PaymentTokenInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
