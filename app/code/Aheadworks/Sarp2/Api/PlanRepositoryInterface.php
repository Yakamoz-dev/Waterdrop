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

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface PlanRepositoryInterface
 * @package Aheadworks\Sarp2\Api
 */
interface PlanRepositoryInterface
{
    /**
     * Save plan
     *
     * @param \Aheadworks\Sarp2\Api\Data\PlanInterface $plan
     * @return \Aheadworks\Sarp2\Api\Data\PlanInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException;
     * @throws \Magento\Framework\Exception\NoSuchEntityException;
     */
    public function save(\Aheadworks\Sarp2\Api\Data\PlanInterface $plan);

    /**
     * Retrieve plan
     *
     * @param int $planId
     * @return \Aheadworks\Sarp2\Api\Data\PlanInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($planId);

    /**
     * Retrieve plans matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Sarp2\Api\Data\PlanSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete plan
     *
     * @param \Aheadworks\Sarp2\Api\Data\PlanInterface $plan
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Aheadworks\Sarp2\Api\Data\PlanInterface $plan);

    /**
     * Delete plan by ID
     *
     * @param int $planId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($planId);
}
