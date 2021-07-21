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
namespace Aheadworks\Sarp2Stripe\Test\Unit\Gateway;

use Aheadworks\Sarp2\Api\Data\PaymentTokenInterface;
use Aheadworks\Sarp2\Api\PaymentTokenRepositoryInterface;
use Aheadworks\Sarp2\Model\Payment\Token\Finder;
use Aheadworks\Sarp2Stripe\Gateway\TokenAssigner;
use Aheadworks\Sarp2Stripe\PaymentData\PaymentIntent\ToToken as PaymentIntentToToken;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Payment\Model\InfoInterface;
use PHPUnit\Framework\TestCase;
use Stripe\PaymentIntent;

class TokenAssignerTest extends TestCase
{
    /**
     * @var Finder
     */
    private $tokenFinderMock;

    /**
     * @var PaymentTokenRepositoryInterface
     */
    private $tokenRepositoryMock;

    /**
     * @var PaymentIntentToToken
     */
    private $paymentIntentToTokenConverter;

    /**
     * @var TokenAssigner
     */
    private $assigner;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->tokenFinderMock = $this->createMock(Finder::class);
        $this->tokenRepositoryMock = $this->getMockForAbstractClass(PaymentTokenRepositoryInterface::class);
        $this->paymentIntentToTokenConverter = $this->createMock(PaymentIntentToToken::class);
        $this->assigner = $objectManager->getObject(
            TokenAssigner::class,
            [
                'tokenFinder' => $this->tokenFinderMock,
                'tokenRepository' => $this->tokenRepositoryMock,
                'paymentIntentToTokenConverter' => $this->paymentIntentToTokenConverter
            ]
        );
    }

    /**
     * Test assign method
     */
    public function testAssign()
    {
        $tokenId = 1;
        $paymentMock = $this->getMockForAbstractClass(InfoInterface::class);
        $paymentIntent = $this->getMockForAbstractClass(PaymentIntent::class);
        $tokenMock = $this->getMockForAbstractClass(PaymentTokenInterface::class);

        $this->paymentIntentToTokenConverter->expects($this->once())
            ->method('convert')
            ->with($paymentIntent)
            ->willReturn($tokenMock);
        $this->tokenFinderMock->expects($this->once())
            ->method('findExisting')
            ->with($tokenMock)
            ->willReturn(null);
        $tokenMock->expects($this->once())
            ->method('setIsActive')
            ->with(true)
            ->willReturn($tokenMock);
        $this->tokenRepositoryMock->expects($this->once())
            ->method('save')
            ->with($tokenMock)
            ->willReturn($tokenMock);
        $paymentMock->expects($this->once())
            ->method('setAdditionalInformation')
            ->with('aw_sarp_payment_token_id', $tokenId)
            ->willReturn($tokenMock);
        $tokenMock->expects($this->once())
            ->method('getTokenId')
            ->willReturn($tokenId);

        $this->assertEquals($paymentMock, $this->assigner->assignBypaymentIntent(
            $paymentMock,
            $paymentIntent
        ));
    }
}
