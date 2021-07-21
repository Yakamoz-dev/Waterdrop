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
namespace Aheadworks\Sarp2\Engine\Payment\Processor\Type;

use Aheadworks\Sarp2\Engine\Payment\ProcessorInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;

/**
 * Class LastPeriodHolder
 * @package Aheadworks\Sarp2\Engine\Payment\Processor\Type
 */
class LastPeriodHolder extends AbstractPayProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($payments)
    {
        $payments = array_filter($payments, [$this, 'isProcessable']);

        if ($payments) {
            foreach ($payments as $payment) {
                $this->increment($payment);
                $this->cleaner->add($payment);
            }
        }

        return $this->resultFactory->create(
            ['isOutstandingDetected' => false]
        );
    }

    /**
     * Check if payment is processable
     *
     * @param $payment
     * @return bool
     */
    private function isProcessable($payment)
    {
        return $this->isProcessableChecker->check($payment, PaymentInterface::TYPE_LAST_PERIOD_HOLDER);
    }
}
