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
use Aheadworks\Sarp2\Model\Config;
use Aheadworks\Sarp2\Model\Config\AdvancedPricingValueResolver;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Configurable;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Configurable\ChildProcessor;
use Aheadworks\Sarp2\Model\Product\Subscription\Option\Processor as SubscriptionOptionProcessor;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\AsLowAsCalculator;
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
 * Test for \Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Configurable
 */
class ConfigurableTest extends TestCase
{
    /**
     * @var \Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Configurable
     */
    private $configurableConfig;

    /**
     * @var ProductRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productRepositoryMock;

    /**
     * @var PlanRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $planRepositoryMock;

    /**
     * @var SubscriptionOptionProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $subscriptionOptionProcessorMock;

    /**
     * @var ChildProcessor|\PHPUnit_Framework_MockObject_MockObject
     */
    private $childProcessorMock;

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
     * @var AsLowAsCalculator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $asLowAsCalculatorMock;

    /**
     * @var Config|\PHPUnit_Framework_MockObject_MockObject
     */
    private $configMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->productRepositoryMock = $this->getMockForAbstractClass(ProductRepositoryInterface::class);
        $this->planRepositoryMock = $this->getMockForAbstractClass(PlanRepositoryInterface::class);
        $this->subscriptionOptionProcessorMock = $this->createMock(SubscriptionOptionProcessor::class);
        $this->childProcessorMock = $this->createMock(ChildProcessor::class);
        $this->taxHelperMock = $this->createMock(TaxHelper::class);
        $this->advancedPricingConfigValueResolverMock = $this->createMock(AdvancedPricingValueResolver::class);
        $this->storeManagementMock = $this->getMockForAbstractClass(StoreManagerInterface::class);
        $this->processorCompositeMock = null;
        $this->asLowAsCalculatorMock = $this->createMock(AsLowAsCalculator::class);
        $this->configMock = $this->createMock(Config::class);

        $this->configurableConfig = $objectManager->getObject(
            Configurable::class,
            [
                'productRepository' => $this->productRepositoryMock,
                'planRepository' => $this->planRepositoryMock,
                'subscriptionOptionProcessor' => $this->subscriptionOptionProcessorMock,
                'childProcessor' => $this->childProcessorMock,
                'taxHelper' => $this->taxHelperMock,
                'advancedPricingConfigValueResolver' => $this->advancedPricingConfigValueResolverMock,
                'storeManagement' => $this->storeManagementMock,
                'asLowAsCalculator' => $this->asLowAsCalculatorMock,
                'config' => $this->configMock,
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
        $childProductId = 2;
        $productType = 'configurable';
        $planId = 10;
        $optionId = 100;
        $currencyFormat = '$%';
        $asLowAsPrice = [
            'price' => [
                'amount' => 10,
                'aw_period' => 'Month'
            ]
        ];
        $isUseAdvancedPricing = [$childProductId => true];
        $optionDetails = [['label' => 'AAA', 'value' => 'BBB']];
        $productPrices = ['final_price' => ['amount' => 55.0]];
        $optionPrices = ['final_price' => ['amount' => 75.0]];
        $installmentsMode = ['enabled' => true, 'billingCycles' => 10, 'isTrial' => true];
        $result = [
            'regularPrices' => [
                'productType' => $productType,
                'options' => [
                    0 => [$childProductId => $productPrices],
                    $optionId => [$childProductId => $optionPrices],
                ],
            ],
            'installmentsMode' => [$optionId => [$childProductId => $installmentsMode]],
            'isUsedAdvancedPricing' => $isUseAdvancedPricing,
            'subscriptionDetails' => [$optionId => [$childProductId => $optionDetails]],
            'productType' => $productType,
            'productId' => $productId,
            'currencyFormat' => $currencyFormat,
            'selectedSubscriptionOptionId' => null,
            'asLowAsPrice' => $asLowAsPrice
        ];

        $productMock = $this->getMockForAbstractClass(ProductInterface::class);
        $productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn($productType);
        $this->productRepositoryMock->expects($this->atLeastOnce())
            ->method('getById')
            ->willReturn($productMock);
        $childProductMock = $this->getMockForAbstractClass(ProductInterface::class);
        $childProductMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($childProductId);
        $this->childProcessorMock->expects($this->atLeastOnce())
            ->method('getAllowedList')
            ->with($productMock)
            ->willReturn([$childProductMock]);
        $this->advancedPricingConfigValueResolverMock->expects($this->atLeastOnce())
            ->method('isUsedAdvancePricing')
            ->willReturn(true);

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

        $this->childProcessorMock->expects($this->atLeastOnce())
            ->method('getSubscriptionOptions')
            ->with($childProductMock, $productId)
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

        $this->subscriptionOptionProcessorMock->expects($this->once())
            ->method('getProductPrices')
            ->with($productMock)
            ->willReturn($productPrices);
        $this->subscriptionOptionProcessorMock->expects($this->once())
            ->method('getOptionPrices')
            ->with($subscriptionOptionMock)
            ->willReturn($optionPrices);

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

        $this->configMock->expects($this->once())
            ->method('isUsedSubscriptionPriceInAsLowAs')
            ->willReturn(true);
        $this->asLowAsCalculatorMock->expects($this->once())
            ->method('calculate')
            ->with($productId)
            ->willReturn($asLowAsPrice);

        $this->assertEquals($result, $this->configurableConfig->getConfig($productId, $productType));
    }

    /**
     * Test getConfig method if no options
     */
    public function testGetConfigNoOptions()
    {
        $productId = 1;
        $childProductId = 2;
        $productType = 'configurable';
        $productPrices = ['final_price' => ['amount' => 55.0]];
        $currencyFormat = '$%';
        $asLowAsPrice = [
            'price' => [
                'amount' => 10,
                'aw_period' => 'Month'
            ]
        ];
        $isUseAdvancedPricing = [$childProductId => true];
        $result = [
            'regularPrices' => [
                'productType' => $productType,
                'options' => [
                    0 => [$childProductId => $productPrices],
                ]
            ],
            'installmentsMode' => [],
            'subscriptionDetails' => [],
            'productType' => $productType,
            'productId' => $productId,
            'isUsedAdvancedPricing' => $isUseAdvancedPricing,
            'currencyFormat' => $currencyFormat,
            'selectedSubscriptionOptionId' => null,
            'asLowAsPrice' => $asLowAsPrice
        ];

        $productMock = $this->getMockForAbstractClass(ProductInterface::class);
        $productMock->expects($this->once())
            ->method('getTypeId')
            ->willReturn($productType);
        $this->productRepositoryMock->expects($this->atLeastOnce())
            ->method('getById')
            ->willReturn($productMock);
        $childProductMock = $this->getMockForAbstractClass(ProductInterface::class);
        $childProductMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($childProductId);
        $this->childProcessorMock->expects($this->atLeastOnce())
            ->method('getAllowedList')
            ->with($productMock)
            ->willReturn([$childProductMock]);
        $this->advancedPricingConfigValueResolverMock->expects($this->atLeastOnce())
            ->method('isUsedAdvancePricing')
            ->willReturn(true);

        $this->childProcessorMock->expects($this->atLeastOnce())
            ->method('getSubscriptionOptions')
            ->with($childProductMock, $productId)
            ->willReturn([]);

        $this->subscriptionOptionProcessorMock->expects($this->once())
            ->method('getProductPrices')
            ->with($productMock)
            ->willReturn($productPrices);

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

        $this->configMock->expects($this->once())
            ->method('isUsedSubscriptionPriceInAsLowAs')
            ->willReturn(true);
        $this->asLowAsCalculatorMock->expects($this->once())
            ->method('calculate')
            ->with($productId)
            ->willReturn($asLowAsPrice);

        $this->assertEquals($result, $this->configurableConfig->getConfig($productId, $productType));
    }

    /**
     * Test getConfig method if an error occurs
     */
    public function testGetConfigException()
    {
        $productId = 1;
        $productType = 'configurable';
        $result = [];

        $this->productRepositoryMock->expects($this->once())
            ->method('getById')
            ->with($productId)
            ->willThrowException(new NoSuchEntityException(__('No such entity!')));

        $this->assertEquals($result, $this->configurableConfig->getConfig($productId, $productType));
    }
}
