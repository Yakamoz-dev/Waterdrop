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
namespace Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Group;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Aheadworks\Sarp2\Api\SubscriptionPriceCalculatorInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\Input as CalculationInput;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\Input\Factory;
use Aheadworks\Sarp2\Model\Sales\Total\Group\AbstractGroup;
use Aheadworks\Sarp2\Model\Sales\Total\PopulatorFactory;
use Aheadworks\Sarp2\Model\Sales\Total\ProviderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\CartItemInterface;

/**
 * Class Trial
 */
class Trial extends AbstractGroup
{
    /**
     * @var CustomOptionCalculator
     */
    private $customOptionsCalculator;

    /**
     * @var Factory
     */
    private $calculationInputFactory;

    /**
     * @param SubscriptionOptionRepositoryInterface $optionRepository
     * @param SubscriptionPriceCalculatorInterface $priceCalculation
     * @param PriceCurrencyInterface $priceCurrency
     * @param PopulatorFactory $populatorFactory
     * @param ProviderInterface $provider
     * @param CustomOptionCalculator $customOptionCalculator
     * @param Factory $calculationInputFactory
     * @param array $populateMaps
     */
    public function __construct(
        SubscriptionOptionRepositoryInterface $optionRepository,
        SubscriptionPriceCalculatorInterface $priceCalculation,
        PriceCurrencyInterface $priceCurrency,
        PopulatorFactory $populatorFactory,
        ProviderInterface $provider,
        CustomOptionCalculator $customOptionCalculator,
        Factory $calculationInputFactory,
        array $populateMaps = []
    ) {
        parent::__construct(
            $optionRepository,
            $priceCalculation,
            $priceCurrency,
            $populatorFactory,
            $provider,
            $populateMaps
        );
        $this->customOptionsCalculator = $customOptionCalculator;
        $this->calculationInputFactory = $calculationInputFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return self::CODE_TRIAL;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemPrice($item, $useBaseCurrency)
    {
        $result = 0.0;
        $quote = $item->getQuote();
        $optionId = $item->getOptionByCode('aw_sarp2_subscription_type');
        if ($optionId) {
            $option = $this->optionRepository->get($optionId->getValue());
            $plan = $option->getPlan();

            if ($plan->getDefinition()->getIsTrialPeriodEnabled()) {
                $calculationInput = $this->createCalculationInput($item);

                $baseItemPrice = $this->priceCalculator->getTrialPrice($calculationInput, $option);
                if ($useBaseCurrency) {
                    $result = $baseItemPrice;
                } else {
                    $currencyForConvert = $quote->getForcedCurrency()
                        ? $quote->getForcedCurrency()->getCode()
                        : null;
                    $result = $this->priceCurrency->convert($baseItemPrice, null, $currencyForConvert);
                }
            }
        }

        $result = $this->customOptionsCalculator->applyOptionsPrice($item, $result, $useBaseCurrency, true);

        return $result;
    }

    /**
     * Create calculation subject from cart item
     *
     * @param CartItemInterface|ProfileItemInterface $item
     * @return CalculationInput
     */
    private function createCalculationInput($item)
    {
        if ($item->getParentItem() && $item->isChildrenCalculated()) {
            $calculationInput = $this->calculationInputFactory->create(
                $item->getParentItem()->getProduct(),
                $item->getParentItem()->getQty(),
                $item->getProduct(),
                $item->getQty()
            );
        } else {
            $calculationInput = $this->calculationInputFactory->create(
                $item->getProduct(),
                $item->getQty()
            );
        }

        return $calculationInput;
    }
}
