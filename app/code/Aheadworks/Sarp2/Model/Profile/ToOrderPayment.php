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
namespace Aheadworks\Sarp2\Model\Profile;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Magento\Framework\DataObject\Copy;
use Magento\Payment\Model\Method\Free;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\Data\OrderPaymentInterfaceFactory;

/**
 * Class ToOrderPayment
 * @package Aheadworks\Sarp2\Model\Profile
 */
class ToOrderPayment
{
    /**
     * @var OrderPaymentInterfaceFactory
     */
    private $orderPaymentFactory;

    /**
     * @var Copy
     */
    private $objectCopyService;

    /**
     * @param OrderPaymentInterfaceFactory $orderPaymentFactory
     * @param Copy $objectCopyService
     */
    public function __construct(
        OrderPaymentInterfaceFactory $orderPaymentFactory,
        Copy $objectCopyService
    ) {
        $this->orderPaymentFactory = $orderPaymentFactory;
        $this->objectCopyService = $objectCopyService;
    }

    /**
     * Convert profile to order payment
     *
     * @param ProfileInterface $profile
     * @param string|null $paymentPeriod
     * @return OrderPaymentInterface
     */
    public function convert(ProfileInterface $profile, $paymentPeriod = null)
    {
        /** @var OrderPaymentInterface $orderPayment */
        $orderPayment = $this->orderPaymentFactory->create();
        if ($paymentPeriod == PaymentInterface::PERIOD_TRIAL
            && $profile->getTrialGrandTotal() + $profile->getBaseTrialShippingAmount() < 0.0001
        ) {
            $paymentMethod = Free::PAYMENT_METHOD_FREE_CODE;
        } else {
            $paymentMethod = 'aw_sarp_' . $profile->getPaymentMethod() . '_recurring';
            $orderPayment->setAdditionalInformation([
                ProfileInterface::PAYMENT_TOKEN_ID => $profile->getPaymentTokenId(),
                ProfileInterface::PROFILE_ID => $profile->getProfileId()
            ]);
        }
        $orderPayment->setMethod($paymentMethod);

        return $orderPayment;
    }
}
