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
 * @version    1.0.5
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Model\Adapter;

use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Aheadworks\Sarp2\Model\Quote\Checker\HasSubscriptions;
use Aheadworks\Sarp2Stripe\Api\SetupIntentManagementInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use StripeIntegration\Payments\Model\StripeCustomer;

/**
 * Class StripeSetupIntentManagement
 *
 * @package Aheadworks\Sarp2Stripe\Model\Adapter
 */
class StripeSetupIntentManagement implements SetupIntentManagementInterface
{
    /**
     * @var HasSubscriptions
     */
    private $quoteChecker;

    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var StripeCustomer
     */
    private $stripeCustomer;

    /**
     * @var ProfileRepositoryInterface
     */
    private $profileRepository;

    /**
     * @var PaymentTokenRepositoryInterface
     */
    private $tokenRepository;

    /**
     * @param \StripeIntegration\Payments\Helper\Generic $helper
     * @param Session $checkoutSession
     * @param HasSubscriptions $quoteChecker
     * @param ProfileRepositoryInterface $profileRepository
     * @param PaymentTokenRepositoryInterface $tokenRepository
     */
    public function __construct(
        \StripeIntegration\Payments\Helper\Generic $helper,
        Session $checkoutSession,
        HasSubscriptions $quoteChecker,
        ProfileRepositoryInterface $profileRepository,
        PaymentTokenRepositoryInterface $tokenRepository
    ) {
        $this->stripeCustomer = $helper->getCustomerModel();
        $this->checkoutSession = $checkoutSession;
        $this->quoteChecker = $quoteChecker;
        $this->profileRepository = $profileRepository;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    public function createForQuote($email)
    {
        /** @var Quote $quote */
        try {
            $quote = $this->checkoutSession->getQuote();
            if ($email != '') {
                $quote->getBillingAddress()->setEmail($email);
            }
        } catch (\Exception $exception) {
            return null;
        }

        if ($this->quoteChecker->check($quote)) {
            $customer = $this->createCustomer();
            return $this->createSetupIntent($customer->id);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function createForProfile($profile_id)
    {
        $profile = $this->profileRepository->get($profile_id);
        try {
            $token = $this->tokenRepository->get($profile->getPaymentTokenId());
            $customerId = $token->getDetails('customerToken');
        } catch (LocalizedException $exception) {
            $customerId = null;
        }

        if (!$customerId) {
            $customer = $this->createCustomer();
            $customerId = $customer->id;
        }

        return $this->createSetupIntent($customerId);
    }

    /**
     * Create stripe customer
     *
     * @return \Stripe\Customer
     * @throws LocalizedException
     */
    private function createCustomer()
    {
        $customer = $this->stripeCustomer->createStripeCustomerIfNotExists(true);
        if (!$customer) {
            throw new LocalizedException(__('Failed to authorize card'));
        }

        return $customer;
    }

    /**
     * Create setupIntent
     *
     * @param string $customerId
     * @return string|null
     */
    private function createSetupIntent($customerId)
    {
        try {
            $setupIntent = \Stripe\SetupIntent::create([
                'usage' => 'off_session',
                "payment_method_types" => ["card"],
                "customer" => $customerId,
                "description" => "Aheadworks SARP2 payment method setup"
            ]);

            return $setupIntent->client_secret;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
