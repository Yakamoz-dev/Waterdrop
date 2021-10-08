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
namespace Aheadworks\Sarp2Stripe\Model\Adapter;

use Aheadworks\Sarp2Stripe\Gateway\Config\Config as StripeConfig;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\CustomerManagerFactory as StripePaymentsCustomerManagerFactory;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\Response;
use Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments\StripeObject\Converter as StripeObjectConverter;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Stripe\Customer as StripeCustomer;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Stripe;
use Stripe\StripeObject;
use StripeIntegration\Payments\Helper\Generic as StripeHelper;

/**
 * Class StripePayments
 * @package Aheadworks\Sarp2Stripe\Model\Adapter
 */
class StripePayments
{
    const CACHED_PAYMENT_INTENT = 'cached_stripe_payment_intent';

    /**
     * @var StripeConfig
     */
    private $config;

    /**
     * @var StripeObjectConverter
     */
    private $stripeObjectConverter;

    /**
     * @var StripePaymentsCustomerManagerFactory
     */
    private $stripePaymentsCustomerManagerFactory;

    /**
     * @var CheckoutSession
     */
    private $session;

    /**
     * @var StripeHelper
     */
    private $stripeHelper;

    /**
     * @param StripeConfig $stripeConfig
     * @param StripeObjectConverter $stripeObjectConverter
     * @param StripePaymentsCustomerManagerFactory $stripePaymentsCustomerManagerFactory
     * @param CheckoutSession $session
     * @param StripeHelper $stripeHelper
     */
    public function __construct(
        StripeConfig $stripeConfig,
        StripeObjectConverter $stripeObjectConverter,
        StripePaymentsCustomerManagerFactory $stripePaymentsCustomerManagerFactory,
        CheckoutSession $session,
        StripeHelper $stripeHelper
    ) {
        $this->config = $stripeConfig;
        $this->stripeObjectConverter = $stripeObjectConverter;
        $this->stripePaymentsCustomerManagerFactory = $stripePaymentsCustomerManagerFactory;
        $this->session = $session;
        $this->stripeHelper = $stripeHelper;
    }

    /**
     * Single payment request
     *
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function singlePayment(array $data)
    {
        $this->initStripe();

        $paymentIntentId = $this->session->getData(self::CACHED_PAYMENT_INTENT, true);
        if ($paymentIntentId) {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        } else {
            $paymentIntent = PaymentIntent::create($data);
        }

        if ($paymentIntent->status == 'requires_confirmation') {
            $paymentIntent->confirm();
        }

        return $this->stripeObjectConverter->toResponse($paymentIntent);
    }

    /**
     * Capture request
     *
     * @param string $id
     * @param float|null $amount
     * @return Response
     * @throws \Exception
     */
    public function capture($id, $amount = null)
    {
        $this->initStripe();
        $intent = PaymentIntent::retrieve($id);

        if ($amount) {
            $intent->capture(['amount_to_capture' => $amount]);
        } else {
            $intent->capture();
        }

        return $this->stripeObjectConverter->toResponse($intent);
    }

    /**
     * Void request
     *
     * @param string $id
     * @return Response
     * @throws \Exception
     */
    public function void($id)
    {
        $this->initStripe();
        $result = $this->cancel($id);

        return $this->stripeObjectConverter->toResponse($result);
    }

    /**
     * Refund request
     *
     * @param string $id
     * @param float|null $amount
     * @return Response
     * @throws \Exception
     */
    public function refund($id, $amount = null)
    {
        $this->initStripe();
        $result = $this->cancel($id, $amount);

        return $this->stripeObjectConverter->toResponse($result);
    }

    /**
     * Retrieve current Stripe customer
     *
     * @param int|null $customerId
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @return StripeCustomer|null
     * @throws LocalizedException
     */
    public function getCurrentCustomer($customerId, $email, $firstname, $lastname)
    {
        $currentCustomer = null;
        $customerId = ($customerId === null) ? 0 : $customerId;
        $this->initStripe();
        $customerManager = $this->stripePaymentsCustomerManagerFactory->getCustomerManager();
        if ($customerManager
            && method_exists($customerManager, 'retrieveByStripeID')
            && method_exists($customerManager, 'createNewStripeCustomer')
        ) {
            $stripeId = $customerManager->getStripeId();
            if ($stripeId) {
                $currentCustomer = $customerManager->retrieveByStripeID($stripeId);
            }

            if ($currentCustomer === null) {
                $currentCustomer = $customerManager->createNewStripeCustomer(
                    $firstname,
                    $lastname,
                    $email,
                    $customerId
                );
            }
        } else {
            throw new LocalizedException(__('AW SARP2: Stripe Payments Customer Manager works incorrectly'));
        }
        return $currentCustomer;
    }

    /**
     * Attach payment method to a customer by payment token if not attached
     *
     * @param string $customerId
     * @param string $paymentToken
     * @return bool
     * @throws LocalizedException
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function attachPaymentMethodToCustomerIfNotAttached($customerId, $paymentToken)
    {
        $result = false;
        $this->initStripe();

        $customerManager = $this->stripePaymentsCustomerManagerFactory->getCustomerManager();
        if ($customerManager
            && method_exists($customerManager, 'retrieveByStripeID')
            && method_exists($customerManager, 'addCard')
        ) {
            $customer = $customerManager->retrieveByStripeID($customerId);
            if ($customer) {
                $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentToken);
                if (isset($paymentMethod->card->fingerprint)) {
                    $card = $this->stripeHelper->findCardByFingerprint(
                        $customer,
                        $paymentMethod->card->fingerprint
                    );

                    if (!$card) {
                        $paymentMethod->attach(['customer' => $customer->id]);
                    }
                }
            }
        } else {
            throw new LocalizedException(__('AW SARP2: Stripe Payments Customer Manager works incorrectly'));
        }
        return $result;
    }

    /**
     * Cancel request
     *
     * @param string $id
     * @param float|null $amount
     * @return StripeObject
     * @throws \Exception
     */
    private function cancel($id, $amount = null)
    {
        $intent = PaymentIntent::retrieve($id);

        if ($intent->status == 'requires_capture') {
            $intent->cancel();
            $result = $intent;
        } else {
            $charge = $intent->charges->data[0];
            $params = [];
            if (!$charge->refunded) {
                $params['charge'] = $charge->id;
                if ($amount) {
                    $params['amount'] = $amount;
                }
                $result = Refund::create($params);
            } else {
                $msg = __('This order has already been refunded in Stripe.'
                    . ' To refund from Magento, please refund it offline.');
                throw new LocalizedException($msg);
            }
        }

        return $result;
    }

    /**
     * Init stripe
     */
    private function initStripe()
    {
        $apiInfo = $this->config->getApiInfo();

        Stripe::setApiVersion('2019-03-14');
        Stripe::setApiKey($this->config->getSecretKey());
        Stripe::setAppInfo($apiInfo['module_name'], $apiInfo['module_version'], $apiInfo['module_url']);
    }
}
