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
namespace Aheadworks\Sarp2\Api;

/**
 * Interface SubscriptionOptionRepositoryInterface
 * @package Aheadworks\Sarp2\Api
 */
interface SubscriptionOptionRepositoryInterface
{
    /**
     * Save subscription option
     *
     * @param \Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface $option
     * @return \Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException;
     * @throws \Magento\Framework\Exception\NoSuchEntityException;
     */
    public function save(\Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface $option);

    /**
     * Retrieve subscription option
     *
     * @param int $optionId
     * @return \Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException;
     */
    public function get($optionId);

    /**
     * Retrieve subscription options for specified product ID
     *
     * @param int $productId
     * @return \Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList($productId);

    /**
     * Delete subscription option by ID
     *
     * @param int $optionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($optionId);
}
