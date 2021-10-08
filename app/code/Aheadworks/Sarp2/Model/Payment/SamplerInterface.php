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
namespace Aheadworks\Sarp2\Model\Payment;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\PaymentInterface as QuotePaymentInfoInterface;

/**
 * Interface SamplerInterface
 * @package Aheadworks\Sarp2\Model\Payment
 */
interface SamplerInterface
{
    /**
     * Import payment data
     *
     * @param SamplerInfoInterface $samplerPaymentInfo
     * @param DataObject $data
     * @return SamplerInfoInterface
     * @throws LocalizedException
     */
    public function assignData(SamplerInfoInterface $samplerPaymentInfo, DataObject $data);

    /**
     * Place payment method
     *
     * @param SamplerInfoInterface $samplerPaymentInfo
     * @param QuotePaymentInfoInterface $quotePaymentInfo
     * @return $this
     */
    public function place(SamplerInfoInterface $samplerPaymentInfo, QuotePaymentInfoInterface $quotePaymentInfo);

    /**
     * Revert payment method
     *
     * @param SamplerInfoInterface $samplerPaymentInfo
     * @return $this
     */
    public function revert(SamplerInfoInterface $samplerPaymentInfo);
}
