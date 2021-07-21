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
namespace Aheadworks\Sarp2\Test\Unit\Model\Product\Subscription\Details\Config;

use Aheadworks\Sarp2\Api\Data\PlanDefinitionInterface;
use Aheadworks\Sarp2\Api\Data\PlanInterface;
use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Aheadworks\Sarp2\Model\Config\AdvancedPricingValueResolver;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Generic;
use Aheadworks\Sarp2\Model\Product\Subscription\Option\Processor as SubscriptionOptionProcessor;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Directory\Model\Currency;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Tax\Helper\Data as TaxHelper;
use PHPUnit\Framework\TestCase;

/**
 * Test for \Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Generic
 */
class GenericTest extends TestCase
{
    /**
     * @var Generic
     */
    private $genericConfig;

    /**
     * @var ProductRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productRepositoryMock;

    /**
     * @var SubscriptionOptionRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $optionsRepositoryMock;

    /**
     * @var PlanRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $planRepositoryMock;

    /**
     * @var SubscriptionOptionProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $subscriptionOptionProcessorMock;

    /**
     * @var TaxHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $taxHelperMock;

    /**
     * @var AdvancedPricingValueResolver|\PHPUnit_Framework_MockObject_MockObject
     */
    private $advancedPricingConfigValueResolverMock;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagementMock;

    /**
     * @var |\PHPUnit_Framework_MockObject_MockObject
     */
    private $processorCompositeMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->productRepositoryMock = $this->getMockForAbstractClass(ProductRepositoryInterface::class);
        $this->optionsRepositoryMock = $this->getMockForAbstractClass(SubscriptionOptionRepositoryInterface::class);
        $this->planRepositoryMock = $this->getMockForAbstractClass(PlanRepositoryInterface::class);
        $this->subscriptionOptionProcessorMock = $this->createMock(SubscriptionOptionProcessor::class);
        $this->taxHelperMock = $this->createMock(TaxHelper::class);
        $this->advancedPricingConfigValueResolverMock = $this->createMock(AdvancedPricingValueResolver::class);
        $this->storeManagementMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->processorCompositeMock = null;

        $this->genericConfig = $objectManager->getObject(
            Generic::class,
            [
                'productRepository' => $this->productRepositoryMock,
                'optionsRepository' => $this->optionsRepositoryMock,
                'planRepository' => $this->planRepositoryMock,
                'subscriptionOptionProcessor' => $this->subscriptionOptionProcessorMock,
                'taxHelper' => $this->taxHelperMock,
                'advancedPricingConfigValueResolver' => $this->advancedPricingConfigValueResolverMock,
                'storeManagement' => $this->storeManagementMock,
                'processorComposite' => $this->processorCompositeMock
            ]
        );
    }

    /**
     * Test getConfig method
     */
    public function testGetConfig()
    {
        $productId = 1;
        $productType = 'simple';
        $planId = 10;
        $optionId = 100;
        $optionDetails = [['label' => 'AAA', 'value' => 'BBB']];
        $productPrices = ['final_price' => ['amount' => 55.0]];
        $optionPrices = ['final_price' => ['amount' => 75.0]];
        $installmentsMode = ['enabled' => true, 'billingCycles' => 10, 'isTrial' => true];
        $currencyFormat = '$%';
        $isUseAdvancedPricing = true;
        $result = [
            'regularPrices' => [
                'productType' => $productType,
                'options' => [
                    0 => $productPrices,
                    $optionId => $optionPrices,
                ],
            ],
            'installmentsMode' => [$optionId => $installmentsMode],
            'subscriptionDetails' => [$optionId => $optionDetails],
            'productType' => $productType,
            'productId' => $productId,
            'isUsedAdvancedPricing' => $isUseAdvancedPricing,
            'currencyFormat' => $currencyFormat,
            'selectedSubscriptionOptionId' => null
        ];

        $subscriptionOptionMock = $this->getMockForAbstractClass(SubscriptionOptionInterface::class);
        $subscriptionOptionMock->expects($this->any())
            ->method('getPlanId')
            ->willReturn($planId);
        $subscriptionOptionMock->expects($this->atLeastOnce())
            ->method('getOptionId')
            ->willReturn($optionId);
        $subscriptionOptionMock->expects($this->any())
            ->method('getIsInstallmentsMode')
            ->willReturn(true);
        $this->optionsRepositoryMock->expects($this->atLeastOnce())
            ->method('getList')
            ->with($productId)
            ->willReturn([$subscriptionOptionMock]);

        $planDefinitionMock = $this->getMockForAbstractClass(PlanDefinitionInterface::class);
        $planDefinitionMock->expects($this->once())
            ->method('getTotalBillingCycles')
            ->willReturn(10);
        $planDefinitionMock->expects($this->once())
            ->method('getIsTrialPeriodEnabled')
            ->willReturn(true);
        $planMock = $this->getMockForAbstractClass(PlanInterface::class);
        $planMock->expects($this->any())
            ->method('getDefinition')
            ->willReturn($planDefinitionMock);
        $this->planRepositoryMock->expects($this->any())
            ->method('get')
            ->with($planId)
            ->willReturn($planMock);

        $this->subscriptionOptionProcessorMock->expects($this->once())
            ->method('getDetailedOptions')
            ->with($subscriptionOptionMock, $planDefinitionMock)
            ->willReturn($optionDetails);

        $productMock = $this->getMockForAbstractClass(ProductInterface::class);
        $productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn($productType);
        $this->productRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($productId)
            ->willReturn($productMock);

        $this->subscriptionOptionProcessorMock->expects($this->once())
            ->method('getProductPrices')
            ->with($productMock)
            ->willReturn($productPrices);
        $this->subscriptionOptionProcessorMock->expects($this->once())
            ->method('getOptionPrices')
            ->with($subscriptionOptionMock)
            ->willReturn($optionPrices);

        $this->advancedPricingConfigValueResolverMock->expects($this->once())
            ->method('isUsedAdvancePricing')
            ->willReturn($isUseAdvancedPricing);

        $storeMock = $this->createMock(Store::class);
        $currencyMock = $this->createMock(Currency::class);
        $currencyMock->expects($this->once())
            ->method('getOutputFormat')
            ->willReturn($currencyFormat);
        $storeMock->expects($this->once())
            ->method('getCurrentCurrency')
            ->willReturn($currencyMock);
        $this->storeManagementMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        $this->assertEquals($result, $this->genericConfig->getConfig($productId, $productType));
    }

    /**
     * Test getConfig method if no options
     */
    public function testGetConfigNoOptions()
    {
        $productId = 1;
        $productType = 'simple';
        $productPrices = ['final_price' => ['amount' => 55.0]];
        $currencyFormat = '$%';
        $isUseAdvancedPricing = true;
        $result = [
            'regularPrices' => [
                'productType' => $productType,
                'options' => [
                    0 => $productPrices,
                    ]
            ],
            'installmentsMode' => [],
            'subscriptionDetails' => [],
            'productType' => $productType,
            'productId' => $productId,
            'isUsedAdvancedPricing' => $isUseAdvancedPricing,
            'currencyFormat' => $currencyFormat,
            'selectedSubscriptionOptionId' => null
        ];

        $this->optionsRepositoryMock->expects($this->atLeastOnce())
            ->method('getList')
            ->with($productId)
            ->willReturn([]);

        $productMock = $this->getMockForAbstractClass(ProductInterface::class);
        $productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn($productType);
        $this->productRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($productId)
            ->willReturn($productMock);

        $this->subscriptionOptionProcessorMock->expects($this->once())
            ->method('getProductPrices')
            ->with($productMock)
            ->willReturn($productPrices);

        $this->advancedPricingConfigValueResolverMock->expects($this->once())
            ->method('isUsedAdvancePricing')
            ->willReturn($isUseAdvancedPricing);

        $storeMock = $this->createMock(Store::class);
        $currencyMock = $this->createMock(Currency::class);
        $currencyMock->expects($this->once())
            ->method('getOutputFormat')
            ->willReturn($currencyFormat);
        $storeMock->expects($this->once())
            ->method('getCurrentCurrency')
            ->willReturn($currencyMock);
        $this->storeManagementMock->expects($this->once())
            ->method('getStore')
            ->willReturn($storeMock);

        $this->assertEquals($result, $this->genericConfig->getConfig($productId, $productType));
    }

    /**
     * Test getConfig method if an error occurs
     */
    public function testGetConfigException()
    {
        $productId = 1;
        $productType = 'simple';
        $result = [];

        $this->productRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($productId)
            ->willThrowException(new NoSuchEntityException(__('No such entity!')));

        $this->assertEquals($result, $this->genericConfig->getConfig($productId, $productType));
    }
}
