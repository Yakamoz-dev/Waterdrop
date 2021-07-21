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
namespace Aheadworks\Sarp2\Model\Quote\Checker;

use Magento\Quote\Model\Quote;

/**
 * Class HasPaymentMethod
 * @package Aheadworks\Sarp2\Model\Quote\Checker
 */
class HasPaymentMethod
{
    /**
     * Check if quote has free payment method
     *
     * @param Quote $quote
     * @return bool
     */
    public function checkFreePayment($quote)
    {
        return $quote->getGrandTotal() <= 0
            && $quote->hasData('aw_sarp_allow_free_payment_method');
    }
}
