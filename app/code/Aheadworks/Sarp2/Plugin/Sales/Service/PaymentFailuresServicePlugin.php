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
namespace Aheadworks\Sarp2\Plugin\Sales\Service;

use Magento\Sales\Model\Service\PaymentFailuresService;

/**
 * Class PaymentFailuresServicePlugin
 *
 * @package Aheadworks\Sarp2\Plugin\Sales\Service
 */
class PaymentFailuresServicePlugin
{
    /**
     * @param PaymentFailuresService $subject
     * @param callable $proceed
     * @param int $cartId
     * @param string $message
     * @param string $checkoutType
     * @return PaymentFailuresService
     */
    public function aroundHandle(
        PaymentFailuresService $subject,
        callable $proceed,
        int $cartId,
        string $message,
        string $checkoutType = 'onepage'
    ) {
        if ($cartId) {
            return $proceed($cartId, $message, $checkoutType);
        } else {
            return $subject;
        }
    }
}
