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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\PriceResolver;

use Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation\Input as CalculationInput;

/**
 * Interface ResolverInterface
 */
interface ResolverInterface
{

    /**
     * Resolve product price
     *
     * @param CalculationInput $subject
     * @param bool $isUsedAdvancePricing
     * @return float
     */
    public function resolveProductPrice(CalculationInput $subject, bool $isUsedAdvancePricing);
}
