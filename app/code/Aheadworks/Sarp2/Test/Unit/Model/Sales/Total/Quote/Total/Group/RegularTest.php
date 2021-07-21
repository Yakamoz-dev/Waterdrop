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
namespace Aheadworks\Sarp2\Test\Unit\Model\Sales\Total\Quote\Total\Group;

use Aheadworks\Sarp2\Api\Data\PlanInterface;
use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Aheadworks\Sarp2\Api\SubscriptionPriceCalculationInterface;
use Aheadworks\Sarp2\Model\Plan\Resolver\ByPeriod\StrategyPool;
use Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Group\BundleOptionCalculator;
use Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Group\CustomOptionCalculator;
use Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Group\Regular;
use Aheadworks\Sarp2\Model\Sales\Total\PopulatorFactory;
use Aheadworks\Sarp2\Model\Sales\Total\ProviderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\Catalog\Model\Product\Configuration\Item\Option\OptionInterface;
use Magento\Quote\Model\Quote\Item;

/**
 * Test for \Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Group\Regular
 */
class RegularTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Calculation regular price
     */
    const CALCULATION_PRICE = 15.00;

    /**
     * @var Regular
     */
    private $totalGroup;

    /**
     * @var SubscriptionOptionRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $optionRepositoryMock;

    /**
     * @var SubscriptionPriceCalculationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $priceCalculationMock;

    /**
     * @var PriceCurrencyInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $priceCurrencyMock;

    /**
     * @var PopulatorFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $populatorFactoryMock;

    /**
     * @var ProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $providerMock;

    /**
     * @var CustomOptionCalculator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customOptionCalculatorMock;

    /**
     * @var BundleOptionCalculator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $bundleOptionsCalculatorMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->optionRepositoryMock = $this->createMock(SubscriptionOptionRepositoryInterface::class);
        $this->priceCalculationMock = $this->createMock(SubscriptionPriceCalculationInterface::class);
        $this->priceCurrencyMock = $this->createMock(PriceCurrencyInterface::class);
        $this->populatorFactoryMock = $this->createMock(PopulatorFactory::class);
        $this->providerMock = $this->createMock(ProviderInterface::class);
        $this->customOptionCalculatorMock = $this->createMock(CustomOptionCalculator::class);
        $this->bundleOptionsCalculatorMock = $this->createMock(BundleOptionCalculator::class);

        $this->totalGroup = $objectManager->getObject(
            Regular::class,
            [
                'optionRepository' => $this->optionRepositoryMock,
                'priceCalculation' => $this->priceCalculationMock,
                'priceCurrency' => $this->priceCurrencyMock,
                'populatorFactory' => $this->populatorFactoryMock,
                'provider' => $this->providerMock,
                'customOptionCalculator' => $this->customOptionCalculatorMock,
                'bundleOptionsCalculator' => $this->bundleOptionsCalculatorMock
            ]
        );
    }

    /**
     * @param bool $useBaseCurrency
     * @param float $expectedResult
     * @throws \ReflectionException
     * @dataProvider getItemPriceDataProvider
     */
    public function testGetItemPrice(
        $useBaseCurrency,
        $expectedResult
    ) {
        $subscriptionOptionId = 1;
        $productId = 2;
        $planId = 1;
        $qty = 1;

        /** @var ItemInterface|\PHPUnit_Framework_MockObject_MockObject $itemMock */
        $itemMock = $this->createMock(Item::class);
        $optionMock = $this->createMock(OptionInterface::class);
        $subscriptionOptionMock = $this->createMock(SubscriptionOptionInterface::class);
        $productMock = $this->createMock(Product::class);

        $itemMock->expects($this->once())
            ->method('getOptionByCode')
            ->with('aw_sarp2_subscription_type')
            ->willReturn($optionMock);
        $optionMock->expects($this->once())
            ->method('getValue')
            ->willReturn($subscriptionOptionId);
        $this->optionRepositoryMock->expects($this->once())
            ->method('get')
            ->with($subscriptionOptionId)
            ->willReturn($subscriptionOptionMock);
        $subscriptionOptionMock->expects($this->once())
            ->method('getPlanId')
            ->willReturn($planId);
        $itemMock->expects($this->once())
            ->method('getQty')
            ->willReturn($qty);
        $itemMock->expects($this->once())
            ->method('getProduct')
            ->willReturn($productMock);
        $productMock->expects($this->once())
            ->method('getEntityId')
            ->willReturn($productId);
        $this->priceCalculationMock->expects($this->once())
            ->method('getRegularPrice')
            ->willReturn(self::CALCULATION_PRICE);
        $this->customOptionCalculatorMock->expects($this->once())
            ->method('applyOptionsPrice')
            ->with($itemMock, self::CALCULATION_PRICE, $useBaseCurrency)
            ->willReturn(self::CALCULATION_PRICE);
        $this->bundleOptionsCalculatorMock->expects($this->once())
            ->method('applyBundlePrice')
            ->with($itemMock, self::CALCULATION_PRICE, $planId, $useBaseCurrency, StrategyPool::TYPE_REGULAR)
            ->willReturn($expectedResult);

        if (!$useBaseCurrency) {
            $this->priceCurrencyMock->expects($this->once())
                ->method('convert')
                ->willReturnArgument(0);
        }

        $this->assertEquals($expectedResult, $this->totalGroup->getItemPrice($itemMock, $useBaseCurrency));
    }

    public function testGetItemPriceNonSubscription()
    {
        /** @var ItemInterface|\PHPUnit_Framework_MockObject_MockObject $itemMock */
        $itemMock = $this->createMock(ItemInterface::class);
        $itemMock->expects($this->once())
            ->method('getOptionByCode')
            ->with('aw_sarp2_subscription_type')
            ->willReturn(null);
        $this->assertEquals(0, $this->totalGroup->getItemPrice($itemMock, true));
    }

    /**
     * @return array
     */
    public function getItemPriceDataProvider()
    {
        return [
            [true, self::CALCULATION_PRICE],
            [false, self::CALCULATION_PRICE],
            [false, self::CALCULATION_PRICE],
            [false, self::CALCULATION_PRICE]
        ];
    }

    /**
     * Get quote item mock
     *
     * @param OptionInterface|\PHPUnit_Framework_MockObject_MockObject $optionMock
     * @param bool $hasChildren
     * @param Product|\PHPUnit_Framework_MockObject_MockObject $productMock
     * @return Item|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getQuoteItemMock($optionMock, $hasChildren, $productMock)
    {
        if ($hasChildren) {
            $itemMock = $this->getConfigurableItemMock($optionMock, $productMock);
        } else {
            $itemMock = $this->getSimpleItemMock($optionMock, $productMock);
        }

        return $itemMock;
    }

    /**
     * Get configurable item mock
     *
     * @param OptionInterface|\PHPUnit_Framework_MockObject_MockObject $optionMock
     * @param Product|\PHPUnit_Framework_MockObject_MockObject $childProductMock
     * @return Item|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getConfigurableItemMock($optionMock, $childProductMock)
    {
        /** @var ItemInterface|\PHPUnit_Framework_MockObject_MockObject $itemMock */
        $itemMock = $this->createMock(Item::class);
        $childItem = $this->createMock(Item::class);
        $itemMock->expects($this->once())
            ->method('getOptionByCode')
            ->with('aw_sarp2_subscription_type')
            ->willReturn($optionMock);
        $itemMock->expects($this->any())
            ->method('__call')
            ->with('getHasChildren')
            ->willReturn(true);
        $itemMock->expects($this->any())
            ->method('getChildren')
            ->willReturn([$childItem]);
        $childItem->expects($this->any())
            ->method('getProduct')
            ->willReturn($childProductMock);

        return $itemMock;
    }

    /**
     * Get simple item mock
     *
     * @param OptionInterface|\PHPUnit_Framework_MockObject_MockObject $optionMock
     * @param Product|\PHPUnit_Framework_MockObject_MockObject $productMock
     * @return Item|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getSimpleItemMock($optionMock, $productMock)
    {
        /** @var ItemInterface|\PHPUnit_Framework_MockObject_MockObject $itemMock */
        $itemMock = $this->createMock(Item::class);
        $itemMock->expects($this->once())
            ->method('getOptionByCode')
            ->with('aw_sarp2_subscription_type')
            ->willReturn($optionMock);
        $itemMock->expects($this->any())
            ->method('__call')
            ->with('getHasChildren')
            ->willReturn(false);
        $itemMock->expects($this->any())
            ->method('getProduct')
            ->willReturn($productMock);

        return $itemMock;
    }
}
