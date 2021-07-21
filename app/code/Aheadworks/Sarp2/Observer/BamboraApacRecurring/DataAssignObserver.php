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
namespace Aheadworks\Sarp2\Observer\BamboraApacRecurring;

use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Class DataAssignObserver
 *
 * @package Aheadworks\Sarp2\Observer\BamboraApacRecurring
 */
class DataAssignObserver extends AbstractDataAssignObserver
{
    /**
     * Gateway vault token
     */
    const GATEWAY_TOKEN = 'gateway_token';

    /**
     * Payment token Id
     */
    const PAYMENT_TOKEN_ID = 'payment_token_id';

    /**
     * @var PaymentTokenRepositoryInterface
     */
    private $tokenRepository;

    /**
     * @param PaymentTokenRepositoryInterface $tokenRepository
     */
    public function __construct(PaymentTokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        $data = $this->readDataArgument($observer);
        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        if (is_array($additionalData)) {
            $paymentInfo = $this->readPaymentModelArgument($observer);
            if (isset($additionalData['token_id'])) {
                $paymentInfo->setAdditionalInformation(
                    self::GATEWAY_TOKEN,
                    $this->tokenRepository->get($additionalData['token_id'])
                );
            }
        }
    }
}
