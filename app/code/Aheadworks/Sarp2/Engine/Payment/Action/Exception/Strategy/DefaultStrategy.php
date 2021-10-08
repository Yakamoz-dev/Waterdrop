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
namespace Aheadworks\Sarp2\Engine\Payment\Action\Exception\Strategy;

use Aheadworks\Sarp2\Engine\Exception\ScheduledPaymentException;

/**
 * Class DefaultStrategy
 *
 * @package Aheadworks\Sarp2\Engine\Payment\Action\Exception\Resolver
 */
class DefaultStrategy implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply($exception)
    {
        $exceptionClass = get_class($exception);
        $message = __('"' . $exceptionClass . '" has been raised with message: ' . $exception->getMessage());

        throw new ScheduledPaymentException(
            $message,
            $exception,
            $exception->getCode()
        );
    }
}
