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
namespace Aheadworks\Sarp2\Api;

use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\Input as CalculationInput;

/**
 * Interface SubscriptionPriceCalculationInterface
 *
 * @package Aheadworks\Sarp2\Api
 */
interface SubscriptionPriceCalculatorInterface
{
    /**
     * Get calculated trial product price for specified plan
     *
     * @param CalculationInput $input
     * @param SubscriptionOptionInterface $option
     * @return float
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTrialPrice($input, $option);

    /**
     * Get calculated regular product price for specified plan
     *
     * @param CalculationInput $input
     * @param SubscriptionOptionInterface $option
     * @return float
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRegularPrice($input, $option);
}
