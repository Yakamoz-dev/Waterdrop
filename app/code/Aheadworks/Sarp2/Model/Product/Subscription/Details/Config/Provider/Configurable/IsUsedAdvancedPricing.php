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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Configurable;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Model\Config\AdvancedPricingValueResolver;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Generic\IsUsedAdvancedPricing as GenericIsUsedAdvancedPricing;

class IsUsedAdvancedPricing extends GenericIsUsedAdvancedPricing
{
    /**
     * @var ChildProcessor
     */
    private $childProcessor;

    /**
     * @param AdvancedPricingValueResolver $advancedPricingConfigValueResolver
     * @param ChildProcessor $childProcessor
     */
    public function __construct(
        AdvancedPricingValueResolver $advancedPricingConfigValueResolver,
        ChildProcessor $childProcessor
    ) {
        parent::__construct($advancedPricingConfigValueResolver);
        $this->childProcessor = $childProcessor;
    }

    /**
     * Get is used advanced pricing config
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
        $childProducts = $this->childProcessor->getAllowedList($product);
        $config = [];
        foreach ($childProducts as $childProduct) {
            $config[$childProduct->getId()] = $this->advancedPricingConfigValueResolver->isUsedAdvancePricing(
                $childProduct->getId()
            );
        }

        return $config;
    }
}
