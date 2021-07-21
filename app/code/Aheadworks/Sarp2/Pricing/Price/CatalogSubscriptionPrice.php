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
namespace Aheadworks\Sarp2\Pricing\Price;

use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Model\Profile\Details\Formatter;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Adjustment\Calculator;
use Magento\Framework\Pricing\Price\AbstractPrice;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Aheadworks\Sarp2\Api\SubscriptionPriceCalculationInterface as PriceCalculation;
use Aheadworks\Sarp2\Model\Product\Subscription\Option\Finder as OptionFinder;

/**
 * Class CatalogSubscriptionPrice
 */
class CatalogSubscriptionPrice extends AbstractPrice
{
    /**
     * @var PriceCalculation
     */
    private $priceCalculation;

    /**
     * @var OptionFinder
     */
    private $finder;

    /**
     * @var PlanRepositoryInterface
     */
    private $planRepository;

    /**
     * @var Formatter
     */
    private $detailsFormatter;

    /**
     * @var SubscriptionOptionInterface[]
     */
    private $optionCache = [];

    /**
     * @param Product $saleableItem
     * @param float $quantity
     * @param Calculator $calculator
     * @param PriceCurrencyInterface $priceCurrency
     * @param PriceCalculation $priceCalculation
     * @param PlanRepositoryInterface $planRepository
     * @param Formatter $detailsFormatter
     * @param OptionFinder $finder
     */
    public function __construct(
        Product $saleableItem,
        $quantity,
        Calculator $calculator,
        PriceCurrencyInterface $priceCurrency,
        PriceCalculation $priceCalculation,
        PlanRepositoryInterface $planRepository,
        Formatter $detailsFormatter,
        OptionFinder $finder
    ) {
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency);
        $this->priceCalculation = $priceCalculation;
        $this->finder = $finder;
        $this->planRepository = $planRepository;
        $this->detailsFormatter = $detailsFormatter;
    }

    /**
     * Returns product subscription value
     *
     * @return float|boolean
     * @throws LocalizedException
     */
    public function getValue()
    {
        return $this->getPriceForCatalog();
    }

    /**
     * Get subscription period
     *
     * @throws LocalizedException
     */
    public function getPeriod()
    {
        $subscriptionOption = $this->getFirstSubscriptionOption($this->product->getId());
        try {
            $plan = $this->planRepository->get($subscriptionOption->getPlanId());
            return $this->detailsFormatter->getFormattedPeriod($plan->getDefinition());
        } catch (\Exception $exception) {
            return '';
        }
    }

    /**
     * Get price for subscription product
     *
     * @return float
     * @throws LocalizedException
     */
    private function getPriceForCatalog()
    {
        $basePrice = 0;
        $subscriptionOption = $this->getFirstSubscriptionOption($this->product->getId());
        if ($subscriptionOption) {
            $basePrice = $this->getBaseRegularPrice($subscriptionOption);
        }

        return $basePrice;
    }

    /**
     * Get base regular price for option
     *
     * @param SubscriptionOptionInterface $option
     * @return float
     * @throws NoSuchEntityException
     */
    private function getBaseRegularPrice($option)
    {
        return $this->priceCalculation->getRegularPrice(
            $option->getProduct()->getId(),
            1,
            $option
        );
    }

    /**
     * Get first subscription option by product id
     *
     * @param int $productId
     * @return SubscriptionOptionInterface|null
     * @throws LocalizedException
     */
    private function getFirstSubscriptionOption($productId)
    {
        if (!array_key_exists($productId, $this->optionCache)) {
            $subscriptionOptions = $this->finder->getSortedOptions($productId);
            if (!empty($subscriptionOptions)) {
                $this->optionCache[$productId] = reset($subscriptionOptions);
            } else {
                $this->optionCache[$productId] = null;
            }
        }

        return $this->optionCache[$productId];
    }
}
