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
 * @package    Sarp2GraphQl
 * @version    1.0.2
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2GraphQl\Test\Unit\Model\Resolver\Profile;

use Aheadworks\Sarp2\Api\Data\ScheduledPaymentInfoInterface;
use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Aheadworks\Sarp2GraphQl\Model\Resolver\ProfileGetNextPaymentInfo;
use Magento\CatalogGraphQl\Model\Resolver\Product\Price\Discount;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class GetNextPaymentInfoTest extends TestCase
{
    /**
     * @var ProfileManagementInterface
     */
    private $profileManagementMock;

    /**
     * @var ProfileRepositoryInterface
     */
    private $profileRepositoryMock;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessorMock;

    /**
     * @var ProfileGetNextPaymentInfo
     */
    private $resolver;

    /**
     * @var Discount
     */
    private $discount;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->profileManagementMock = $this->getMockForAbstractClass(ProfileManagementInterface::class);
        $this->profileRepositoryMock = $this->getMockForAbstractClass(ProfileRepositoryInterface::class);
        $this->dataObjectProcessorMock = $this->createMock(DataObjectProcessor::class);
        $this->resolver = $objectManager->getObject(
            ProfileGetNextPaymentInfo::class,
            [
                'profileManagement' => $this->profileManagementMock,
                'profileRepository' => $this->profileRepositoryMock,
                'dataObjectProcessor' => $this->dataObjectProcessorMock
            ]
        );
    }

    /**
     * Test performResolve method
     */
    public function testPerformResolve()
    {
        $fieldMock = $this->createMock(Field::class);
        $contextMock = $this->getMockForAbstractClass(ContextInterface::class);
        $resolveInfoMock = $this->createMock(ResolveInfo::class);
        $profileId = 1;
        $args = ['profile_id' => $profileId];
        $paymentInfoMock = $this->getMockForAbstractClass(ScheduledPaymentInfoInterface::class);
        $paymentInfoArray = [
            'amount' => 1,
            'baseAmount' => 1
        ];

        $this->profileManagementMock->expects($this->once())
            ->method('getNextPaymentInfo')
            ->with($profileId)
            ->willReturn($paymentInfoMock);
        $this->dataObjectProcessorMock->expects($this->once())
            ->method('buildOutputDataArray')
            ->willReturn($paymentInfoArray);

        $this->assertEquals($paymentInfoArray, $this->resolver->performResolve(
            $fieldMock,
            $contextMock,
            $resolveInfoMock,
            [],
            $args
        ));
    }
}
