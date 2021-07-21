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

use Aheadworks\Sarp2\Api\Data\PaymentTokenInterface;
use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Aheadworks\Sarp2\Test\Integration\PaymentData\Braintree\Adapter\ClientStub;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var PaymentTokenInterface $paymentToken */
$paymentToken = $objectManager->create(PaymentTokenInterface::class);
$paymentToken
    ->setType('card')
    ->setPaymentMethod('braintree')
    ->setTokenValue(ClientStub::TOKEN);

$expiresAt = (new \DateTime())
    ->add(new \DateInterval('P5Y'))
    ->format('Y-m-d 00:00:00');
$paymentToken->setExpiresAt($expiresAt);

/** @var PaymentTokenRepositoryInterface $tokenRepository */
$tokenRepository = $objectManager->create(PaymentTokenRepositoryInterface::class);
$tokenRepository->save($paymentToken);
