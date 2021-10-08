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
namespace Aheadworks\Sarp2\Engine\Payment\Failure;

use Aheadworks\Sarp2\Engine\PaymentInterface;
use Magento\Framework\DataObject;

/**
 * Interface HandlerInterface
 * @package Aheadworks\Sarp2\Engine\Payment\Failure
 */
interface HandlerInterface
{
    /**
     * Failure handler types
     */
    const TYPE_SINGLE = 'single';
    const TYPE_BUNDLE = 'bundle';

    /**
     * Handle payment exception
     *
     * @param PaymentInterface $payment
     * @param DataObject|null $failureInfo
     * @return PaymentInterface
     */
    public function handle($payment, $failureInfo = null);

    /**
     * Handle payment reattempt exception
     *
     * @param PaymentInterface $payment
     * @param DataObject|null $failureInfo
     * @return PaymentInterface
     */
    public function handleReattempt($payment, $failureInfo = null);
}
