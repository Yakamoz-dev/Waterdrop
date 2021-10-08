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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Generic;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Model\Config\AdvancedPricingValueResolver;
use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\ConfigInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\LocalizedException;

class IsUsedAdvancedPricing implements ConfigInterface
{
    /**
     * @var AdvancedPricingValueResolver
     */
    protected $advancedPricingConfigValueResolver;

    /**
     * @param AdvancedPricingValueResolver $advancedPricingConfigValueResolver
     */
    public function __construct(
        AdvancedPricingValueResolver $advancedPricingConfigValueResolver
    ) {
        $this->advancedPricingConfigValueResolver = $advancedPricingConfigValueResolver;
    }

    /**
     * Get is used advanced pricing config
     *
     * @param ProductInterface $product
     * @param ProfileItemInterface|null $item
     * @param ProfileInterface|null $profile
     * @return bool
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getConfig($product, $item = null, $profile = null)
    {
        return $this->advancedPricingConfigValueResolver->isUsedAdvancePricing($product->getId());
    }
}
