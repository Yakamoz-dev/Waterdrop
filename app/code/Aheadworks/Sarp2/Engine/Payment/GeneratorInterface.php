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
namespace Aheadworks\Sarp2\Engine\Payment;

use Aheadworks\Sarp2\Engine\Payment\Generator\SourceInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;

/**
 * Interface GeneratorInterface
 * @package Aheadworks\Sarp2\Engine\Payment
 */
interface GeneratorInterface
{
    /**
     * Generate payments.
     * Assumed that this include only planning of payments on particular time.
     * This include:
     * - profile creation -> first planned payments;
     * - actual payments paid -> next planned payments;
     * - actual payments failed -> planned reattempts
     *
     * @param SourceInterface $source
     * @return PaymentInterface[]
     */
    public function generate(SourceInterface $source);
}
