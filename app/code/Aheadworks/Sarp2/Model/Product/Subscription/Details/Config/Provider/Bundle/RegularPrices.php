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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Bundle;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Aheadworks\Sarp2\Model\Product\Subscription\Option\Calculator\TierPriceCalculator;
use Aheadworks\Sarp2\Model\Product\Subscription\Option\Processor as SubscriptionOptionProcessor;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Locale\Format as LocaleFormat;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Generic\RegularPrices as GenericRegularPrices;
use Magento\Tax\Helper\Data as TaxHelper;

class RegularPrices extends GenericRegularPrices
{
    /**
     * @var TierPriceCalculator
     */
    private $tierPriceCalculation;

    /**
     * @var LocaleFormat
     */
    private $localeFormat;

    /**
     * @param SubscriptionOptionRepositoryInterface $optionsRepository
     * @param PlanRepositoryInterface $planRepository
     * @param SubscriptionOptionProcessor $subscriptionOptionProcessor
     * @param TaxHelper $taxHelper
     * @param TierPriceCalculator $tierPriceCalculation
     * @param LocaleFormat $localeFormat
     */
    public function __construct(
        SubscriptionOptionRepositoryInterface $optionsRepository,
        PlanRepositoryInterface $planRepository,
        SubscriptionOptionProcessor $subscriptionOptionProcessor,
        TaxHelper $taxHelper,
        TierPriceCalculator $tierPriceCalculation,
        LocaleFormat $localeFormat
    ) {
        parent::__construct($optionsRepository, $planRepository, $subscriptionOptionProcessor, $taxHelper);
        $this->tierPriceCalculation = $tierPriceCalculation;
        $this->localeFormat = $localeFormat;
    }

    /**
     * Get regular prices config
     *
     * @param ProductInterface $product
     * @param ProfileItemInterface|null $item
     * @param ProfileInterface|null $profile
     * @return array
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getConfig($product, $item = null, $profile = null)
    {
        $oldPrice = $product->getPriceModel()->getPrice($product);
        $basePrice = $product->getPriceModel()->getBasePrice($product, 1);
        $finalPrice = $product->getPriceModel()->getFinalPrice(1, $product);
        $tierPrices = $this->tierPriceCalculation->getRegularTierPrices($product);

        $priceOptions = [0 => [
            'oldPrice' => [
                'amount' => $this->localeFormat->getNumber($oldPrice),
            ],
            'basePrice' => [
                'amount' => $this->localeFormat->getNumber($basePrice),
            ],
            'finalPrice' => [
                'amount' => $this->localeFormat->getNumber($finalPrice),
            ],
            'tierPrices' => $tierPrices,
        ]];

        $subscriptionOptions = $this->optionsRepository->getList($product->getId());

        foreach ($subscriptionOptions as $option) {
            $priceOptions[$option->getOptionId()] = $this->getPriceOption($option, $item);
        }

        return [
            'productType' => $product->getTypeId(),
            'options' => $priceOptions
        ];
    }
}
