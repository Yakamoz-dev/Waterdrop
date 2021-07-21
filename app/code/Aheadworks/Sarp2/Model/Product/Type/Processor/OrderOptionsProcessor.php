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
namespace Aheadworks\Sarp2\Model\Product\Type\Processor;

use Aheadworks\Sarp2\Api\Data\PlanInterface;
use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface as Payment;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Reflection\DataObjectProcessor;

/**
 * Class OrderOptionsProcessor
 *
 * @package Aheadworks\Sarp2\Model\Product\Type\Processor
 */
class OrderOptionsProcessor
{
    /**
     * @var SubscriptionOptionRepositoryInterface
     */
    private $subscriptionOptionRepository;

    /**
     * @var PlanRepositoryInterface
     */
    private $planRepository;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @param SubscriptionOptionRepositoryInterface $subscriptionOptionRepository
     * @param PlanRepositoryInterface $planRepository
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        SubscriptionOptionRepositoryInterface $subscriptionOptionRepository,
        PlanRepositoryInterface $planRepository,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->subscriptionOptionRepository = $subscriptionOptionRepository;
        $this->planRepository = $planRepository;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Process order options
     *
     * @param Product $product
     * @param array $options
     * @return array
     */
    public function process($product, &$options)
    {
        $optionId = $product->getCustomOption('aw_sarp2_subscription_type');
        if ($optionId) {
            try {
                $subscriptionOption = $this->getSubscriptionOption($optionId->getValue());
                $options['aw_sarp2_subscription_option'] = $this->getSubscriptionOptionDataArray($optionId->getValue());
                $options['aw_sarp2_subscription_plan'] = $this->getPlanDataArray($subscriptionOption->getPlanId());
                $options['aw_sarp2_subscription_payment_period'] = Payment::PERIOD_INITIAL;
            } catch (LocalizedException $e) {
            }
        }

        return $options;
    }

    /**
     * Retrieve subscription option by option id
     *
     * @param int $optionId
     * @return SubscriptionOptionInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getSubscriptionOption($optionId)
    {
        return $this->subscriptionOptionRepository->get($optionId);
    }

    /**
     * Retrieve subscription option data array by option id
     *
     * @param int $optionId
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getSubscriptionOptionDataArray($optionId)
    {
        $option = $this->subscriptionOptionRepository->get($optionId);
        $optionArray = $this->dataObjectProcessor->buildOutputDataArray(
            $option,
            SubscriptionOptionInterface::class
        );
        unset($optionArray[SubscriptionOptionInterface::PLAN]);
        unset($optionArray[SubscriptionOptionInterface::PRODUCT]);

        return $optionArray;
    }

    /**
     * Retrieve subscription plan data array by plan id
     *
     * @param int $planId
     * @return array
     * @throws LocalizedException
     */
    private function getPlanDataArray($planId)
    {
        $plan = $this->planRepository->get($planId);

        return $this->dataObjectProcessor->buildOutputDataArray(
            $plan,
            PlanInterface::class
        );
    }
}
