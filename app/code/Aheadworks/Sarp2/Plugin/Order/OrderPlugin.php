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
namespace Aheadworks\Sarp2\Plugin\Order;

use Aheadworks\Sarp2\Model\Sales\Order\Payment\Processor as PaymentMethodProcessor;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Model\Order;

/**
 * Class OrderPlugin
 *
 * @package Aheadworks\Sarp2\Plugin\Payment\Helper
 */
class OrderPlugin
{
    /**
     * @var PaymentMethodProcessor
     */
    private $paymentMethodProcessor;

    /**
     * @param PaymentMethodProcessor $paymentMethodProcessor
     */
    public function __construct(
        PaymentMethodProcessor $paymentMethodProcessor
    ) {
        $this->paymentMethodProcessor = $paymentMethodProcessor;
    }

    /**
     * Modify free order payment instance
     *
     * @param Order $subject
     * @param OrderPaymentInterface $paymentInfo
     * @return OrderPaymentInterface|null
     */
    public function afterGetPayment(Order $subject, $paymentInfo)
    {
        if ($paymentInfo) {
            $this->paymentMethodProcessor->replaceFreeMethodInstance($paymentInfo);
        }

        return $paymentInfo;
    }
}
