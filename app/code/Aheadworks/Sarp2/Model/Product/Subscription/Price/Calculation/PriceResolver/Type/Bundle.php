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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\PriceResolver\Type;

use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\Input;
use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\PriceResolver\ResolverInterface;
use Aheadworks\Sarp2\Model\Product\Type\Bundle\PriceModelSubstitute;

/**
 * Class Bundle
 */
class Bundle implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolveProductPrice(Input $subject, bool $isUsedAdvancePricing)
    {
        $product = $subject->getProduct();
        if (!$isUsedAdvancePricing) {
            $product->setData(PriceModelSubstitute::DO_NOT_USE_ADVANCED_PRICES_FOR_BUNDLE, true);
        }

        $priceModel = $product->getPriceModel();

        if ($subject->isChildrenCalculated()) {
            return $priceModel->getChildFinalPrice(
                $subject->getProduct(),
                $subject->getQty(),
                $subject->getChildProduct(),
                $subject->getChildQty()
            );
        } else {
            return $priceModel->getFinalPrice(
                $subject->getQty(),
                $subject->getProduct()
            );
        }
    }
}
