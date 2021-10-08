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

/**
 * Class Configurable
 */
class Configurable implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolveProductPrice(Input $subject, bool $isUsedAdvancePricing)
    {
        $product = $subject->isChildrenCalculated()
            ? $subject->getChildProduct()
            : $subject->getProduct();
        $qty = $subject->isChildrenCalculated()
            ? $subject->getChildQty()
            : $subject->getQty();
        $priceModel = $product->getPriceModel();

        return $isUsedAdvancePricing
            ? $priceModel->getFinalPrice($qty, $product)
            : $priceModel->getPrice($product);
    }
}
