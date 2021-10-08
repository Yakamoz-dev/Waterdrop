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
 * @package    Sarp2Stripe
 * @version    1.0.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Sampler\Gateway;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use StripeIntegration\Payments\Model\StripeCustomer;

/**
 * Class CustomerId
 *
 * @package Aheadworks\Sarp2Stripe\Sampler\Gateway
 */
class CustomerId
{
    /**
     * @var PaymentTokenRepositoryInterface
     */
    private $paymentTokenRepository;

     /**
     * @var StripeCustomer
     */
    private $stripeCustomer;

    /**
     * @param PaymentTokenRepositoryInterface $paymentTokenRepository
     * @param \StripeIntegration\Payments\Helper\Generic $helper
     */
    public function __construct(
        PaymentTokenRepositoryInterface $paymentTokenRepository,
        \StripeIntegration\Payments\Helper\Generic $helper
    ) {
        $this->paymentTokenRepository = $paymentTokenRepository;
        $this->stripeCustomer = $helper->getCustomerModel();
    }

    /**
     * Resolve Stripe customer id value by subscription profile
     *
     * @param ProfileInterface $profile
     * @return string|null
     */
    public function resolve($profile)
    {
        try {
            $customerToken = $this->getSavedCustomerToken($profile);
            if (empty($customerToken)) {
                $currentCustomer = $this->stripeCustomer->createStripeCustomerIfNotExists(true);
                if ($currentCustomer) {
                    $customerToken = $currentCustomer->id;
                }
            }
        } catch (\Exception $exception) {
            $customerToken = null;
        }

        return $customerToken;
    }

    /**
     * Get saved customer token
     *
     * @param ProfileInterface $profile
     * @return string|null
     */
    private function getSavedCustomerToken($profile)
    {
        try {
            $paymentTokenId = $profile->getPaymentTokenId();
            $token = $this->paymentTokenRepository->get($paymentTokenId);
            $customerToken = $token->getDetails('customerToken');
        } catch (LocalizedException $e) {
            $customerToken = null;
        }

        return $customerToken;
    }
}
