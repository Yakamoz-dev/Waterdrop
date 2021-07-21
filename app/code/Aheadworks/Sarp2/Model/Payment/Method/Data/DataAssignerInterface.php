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
namespace Aheadworks\Sarp2\Model\Payment\Method\Data;

use Magento\Payment\Model\InfoInterface;

/**
 * Interface DataAssignerInterface
 *
 * @package Aheadworks\Sarp2\Model\Payment\Method\Data
 */
interface DataAssignerInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     */
    const IS_SARP_TOKEN_ENABLED = 'is_aw_sarp_payment_token_enabled';
    const TOKEN_ID = 'token_id';
    const GATEWAY_TOKEN = 'gateway_token';
    const PAYMENT_TOKEN_ID = 'payment_token_id';
    /**#@-*/

    /**
     * Assign additional data to base payment method
     *
     * @param InfoInterface $paymentInfo
     * @param array $additionalData
     * @return void
     */
    public function assignDataToBaseMethod(InfoInterface $paymentInfo, array $additionalData);

    /**
     * Assign additional data to recurrent payment method
     *
     * @param InfoInterface $paymentInfo
     * @param array $additionalData
     * @return void
     */
    public function assignDataToRecurringMethod(InfoInterface $paymentInfo, array $additionalData);
}
