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

use Aheadworks\Sarp2\Model\Product\Checker\IsSubscription;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;

/**
 * Class CartCandidatesProcessor
 *
 * @package Aheadworks\Sarp2\Model\Product\Type\Processor
 */
class CartCandidatesProcessor
{
    /**
     * @var IsSubscription
     */
    private $isSubscriptionChecker;

    /**
     * @param IsSubscription $isSubscriptionChecker
     */
    public function __construct(IsSubscription $isSubscriptionChecker)
    {
        $this->isSubscriptionChecker = $isSubscriptionChecker;
    }

    /**
     * Process add to cart candidates
     *
     * @param DataObject $buyRequest
     * @param Product[]|string $candidates
     * @return Product[]
     */
    public function process(DataObject $buyRequest, $candidates)
    {
        if (is_array($candidates)) {
            foreach ($candidates as $candidate) {
                if ($this->isSubscriptionChecker->check($candidate)) {
                    $optionId = $this->getOptionId($buyRequest);
                    if ($optionId) {
                        $candidate->addCustomOption('aw_sarp2_subscription_type', $optionId);
                    } elseif ($this->isSubscriptionChecker->check($candidate, true)) {
                        $candidates = $candidate->getTypeInstance()
                            ->getSpecifyOptionMessage()
                            ->render();
                    }
                }
            }
        }
        return $candidates;
    }

    /**
     * Get option id from buyRequest
     *
     * @param DataObject $buyRequest
     * @return int|null
     */
    private function getOptionId($buyRequest)
    {
        if ($buyRequest->getData('aw_sarp2_subscription_type') != null) {
            return (int)$buyRequest->getData('aw_sarp2_subscription_type');
        } elseif (isset($buyRequest->getData('options')['aw_sarp2_subscription_type']) &&
            $buyRequest->getData('options')['aw_sarp2_subscription_type'] != null
        ) {
            return (int)$buyRequest->getData('options')['aw_sarp2_subscription_type'];
        }

        return null;
    }
}
