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
namespace Aheadworks\Sarp2\PaymentData;

use Magento\Payment\Model\InfoInterface;
use Magento\Quote\Model\Quote;

/**
 * Interface PaymentInterface
 * @package Aheadworks\Sarp2\PaymentData
 */
interface PaymentInterface
{
    /**
     * Get payment info
     *
     * @return InfoInterface
     */
    public function getPaymentInfo();

    /**
     * Get quote
     *
     * @return Quote
     */
    public function getQuote();
}
