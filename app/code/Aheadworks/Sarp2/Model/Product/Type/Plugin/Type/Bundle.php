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
namespace Aheadworks\Sarp2\Model\Product\Type\Plugin\Type;

use Aheadworks\Sarp2\Model\Product\Checker\IsSubscription;
use Aheadworks\Sarp2\Model\Product\Type\Processor\CartCandidatesProcessor;
use Aheadworks\Sarp2\Model\Product\Type\Processor\OrderOptionsProcessor;
use Magento\Bundle\Model\Product\Type as BundleProductType;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;

/**
 * Class Bundle
 * @package Aheadworks\Sarp2\Model\Product\Type\Plugin\Type
 */
class Bundle
{
    /**
     * @var IsSubscription
     */
    private $isSubscriptionChecker;

    /**
     * @var CartCandidatesProcessor
     */
    private $cartCandidatesProcessor;

    /**
     * @var OrderOptionsProcessor
     */
    private $orderOptionsProcessor;

    /**
     * @param IsSubscription $isSubscriptionChecker
     * @param CartCandidatesProcessor $cartCandidatesProcessor
     * @param OrderOptionsProcessor $orderOptionsProcessor
     */
    public function __construct(
        IsSubscription $isSubscriptionChecker,
        CartCandidatesProcessor $cartCandidatesProcessor,
        OrderOptionsProcessor $orderOptionsProcessor
    ) {
        $this->isSubscriptionChecker = $isSubscriptionChecker;
        $this->cartCandidatesProcessor = $cartCandidatesProcessor;
        $this->orderOptionsProcessor = $orderOptionsProcessor;
    }

    /**
     * @param BundleProductType $subject
     * @param \Closure $proceed
     * @param Product $product
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundHasOptions(BundleProductType $subject, \Closure $proceed, $product)
    {
        return $proceed($product) || $this->isSubscriptionChecker->check($product);
    }

    /**
     * @param BundleProductType $subject
     * @param \Closure $proceed
     * @param Product $product
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundHasRequiredOptions(BundleProductType $subject, \Closure $proceed, $product)
    {
        return $proceed($product) || $this->isSubscriptionChecker->check($product, true);
    }

    /**
     * @param BundleProductType $subject
     * @param \Closure $proceed
     * @param DataObject $buyRequest
     * @param Product $product
     * @param null|string $processMode
     * @return array|string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundPrepareForCartAdvanced(
        BundleProductType $subject,
        \Closure $proceed,
        DataObject $buyRequest,
        $product,
        $processMode = null
    ) {
        $candidates = $proceed($buyRequest, $product, $processMode);
        $candidates = $this->cartCandidatesProcessor->process($buyRequest, $candidates);
        $subscriptionType = $this->getSubscriptionTypeFromParent($candidates);
        if ($subscriptionType) {
            $candidates = $this->updateSubscriptionTypeForChildItems($candidates, $subscriptionType);
        }

        return $candidates;
    }

    /**
     * Retrieve subscription type option value from parent bundle candidate
     *
     * @param Product[]$candidates
     * @return int|null
     */
    private function getSubscriptionTypeFromParent($candidates)
    {
        foreach ($candidates as $candidate) {
            if ($candidate->getTypeId() == BundleProductType::TYPE_CODE) {
                $option = $candidate->getCustomOption('aw_sarp2_subscription_type');
                if ($option) {
                    return $option->getValue();
                }
            }
        }

        return null;
    }

    /**
     * Update subscription type for child items
     *
     * @param Product[] $candidates
     * @return Product[]
     */
    private function updateSubscriptionTypeForChildItems($candidates, $type)
    {
        foreach ($candidates as $candidate) {
            if ($candidate->getTypeId() != BundleProductType::TYPE_CODE) {
                $candidate->addCustomOption('aw_sarp2_subscription_type', $type);
            }
        }

        return $candidates;
    }

    /**
     * @param BundleProductType $subject
     * @param \Closure $proceed
     * @param Product $product
     * @return array|string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetOrderOptions(
        BundleProductType $subject,
        \Closure $proceed,
        $product
    ) {
        $options = $proceed($product);
        $this->orderOptionsProcessor->process($product, $options);

        return $options;
    }
}
