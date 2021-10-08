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
namespace Aheadworks\Sarp2\Engine\Payment\Processor\Handler\AfterPlaceOrder;

use Aheadworks\Sarp2\Engine\Payment\Processor\Handler\HandlerInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;

/**
 * Class Composite
 *
 * @package Aheadworks\Sarp2\Engine\Payment\Processor\Handler\AfterPlaceOrder
 */
class Composite implements HandlerInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $handlerList;

    /**
     * Composite constructor.
     *
     * @param HandlerInterface[] $handlerList
     */
    public function __construct(array $handlerList = [])
    {
        $this->handlerList = $handlerList;
    }

    /**
     * Process payment
     *
     * @param PaymentInterface $payment
     * @return void
     */
    public function handle(PaymentInterface $payment)
    {
        foreach ($this->handlerList as $handler) {
            $handler->handle($payment);
        }
    }
}
