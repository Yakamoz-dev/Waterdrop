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
namespace Aheadworks\Sarp2Stripe\Block\Customer;

use Aheadworks\Sarp2\Model\Payment\Token\Finder as PaymentTokenFinder;
use Aheadworks\Sarp2\Model\Profile\Finder as ProfileFinder;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class StripeCardsChecker
 * @package Aheadworks\Sarp2Stripe\Block\Customer
 */
class StripeCardsChecker extends Template
{
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var ProfileFinder
     */
    private $profileFinder;

    /**
     * @var PaymentTokenFinder
     */
    private $paymentTokenFinder;

    /**
     * @var JsonSerializer
     */
    private $serializer;

    /**
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param ProfileFinder $profileFinder
     * @param PaymentTokenFinder $paymentTokenFinder
     * @param JsonSerializer $serializer
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        ProfileFinder $profileFinder,
        PaymentTokenFinder $paymentTokenFinder,
        JsonSerializer $serializer,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->profileFinder = $profileFinder;
        $this->paymentTokenFinder = $paymentTokenFinder;
        $this->serializer = $serializer;
    }

    /**
     * Get active subcriptions tokens
     *
     * @return array
     */
    public function getActiveSubscriptionTokens()
    {
        $activeTokens = [];
        $customerId = $this->customerSession->getCustomerId();
        if ($customerId) {
            $profiles = $this->profileFinder->getActiveProfilesByCustomerId($customerId);
            $tokenIds = [];
            foreach ($profiles as $profile) {
                $tokenIds[] = $profile->getPaymentTokenId();
            }
            $paymentTokens = $this->paymentTokenFinder->findExistingByIds($tokenIds, 'stripe_payments');
            foreach ($paymentTokens as $paymentToken) {
                $activeTokens[] = $paymentToken->getTokenValue();
            }
        }

        return $activeTokens;
    }

    /**
     * Serialize data to json string
     *
     * @param mixed $data
     * @return bool|false|string
     */
    public function jsonEncode($data)
    {
        return $this->serializer->serialize($data);
    }
}
