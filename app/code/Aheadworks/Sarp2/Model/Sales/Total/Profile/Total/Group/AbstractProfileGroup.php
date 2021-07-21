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
namespace Aheadworks\Sarp2\Model\Sales\Total\Profile\Total\Group;

use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterfaceFactory;
use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Aheadworks\Sarp2\Api\SubscriptionPriceCalculationInterface;
use Aheadworks\Sarp2\Model\Profile\Item;
use Aheadworks\Sarp2\Model\Profile\Item\Options\Extractor as OptionExtractor;
use Aheadworks\Sarp2\Model\Sales\Total\Group\AbstractGroup;
use Aheadworks\Sarp2\Model\Sales\Total\PopulatorFactory;
use Aheadworks\Sarp2\Model\Sales\Total\ProviderInterface;
use Magento\Bundle\Model\Product\Type as BundleType;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Class AbstractProfileGroup
 * @package Aheadworks\Sarp2\Model\Sales\Total\Profile\Total\Group
 */
abstract class AbstractProfileGroup extends AbstractGroup
{
    /**
     * @var SubscriptionOptionInterfaceFactory
     */
    protected $subscriptionOptionFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var CustomOptionCalculator
     */
    protected $customOptionCalculator;

    /**
     * @var BundleOptionCalculator
     */
    protected $bundleOptionCalculator;

    /**
     * @var OptionExtractor
     */
    protected $optionExtractor;

    /**
     * @param SubscriptionOptionRepositoryInterface $optionRepository
     * @param SubscriptionPriceCalculationInterface $priceCalculation
     * @param PriceCurrencyInterface $priceCurrency
     * @param PopulatorFactory $populatorFactory
     * @param ProviderInterface $provider
     * @param SubscriptionOptionInterfaceFactory $subscriptionOptionFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param CustomOptionCalculator $customOptionCalculator
     * @param BundleOptionCalculator $bundleOptionCalculator
     * @param OptionExtractor $subscriptionOptionExtractor
     * @param array $populateMaps
     */
    public function __construct(
        SubscriptionOptionRepositoryInterface $optionRepository,
        SubscriptionPriceCalculationInterface $priceCalculation,
        PriceCurrencyInterface $priceCurrency,
        PopulatorFactory $populatorFactory,
        ProviderInterface $provider,
        SubscriptionOptionInterfaceFactory $subscriptionOptionFactory,
        DataObjectHelper $dataObjectHelper,
        CustomOptionCalculator $customOptionCalculator,
        BundleOptionCalculator $bundleOptionCalculator,
        OptionExtractor $subscriptionOptionExtractor,
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
        $this->subscriptionOptionFactory = $subscriptionOptionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->customOptionCalculator = $customOptionCalculator;
        $this->bundleOptionCalculator = $bundleOptionCalculator;
        $this->optionExtractor = $subscriptionOptionExtractor;
    }

    /**
     * Retrieve item option
     *
     * @param Item $item
     * @return \Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getItemOption($item)
    {
        return $this->optionExtractor->getSubscriptionOption($item);
    }

    /**
     * Get product
     *
     * @param Item $item
     * @return ProductInterface
     */
    protected function getProduct($item)
    {
        if ($item->hasChildItems()
            && $item->getProductType() != BundleType::TYPE_CODE
        ) {
            $children = $item->getChildItems();
            $child = reset($children);
            $product = $child->getProduct();
        } else {
            $product = $item->getProduct();
        }
        return $product;
    }
}
