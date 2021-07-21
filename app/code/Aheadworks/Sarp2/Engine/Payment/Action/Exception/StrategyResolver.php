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
namespace Aheadworks\Sarp2\Engine\Payment\Action\Exception;

use Aheadworks\Sarp2\Engine\Payment\Action\Exception\Strategy\DefaultStrategy;
use Aheadworks\Sarp2\Engine\Payment\Action\Exception\Strategy\StrategyInterface;

/**
 * Class StrategyResolver
 *
 * @package Aheadworks\Sarp2\Engine\Payment\Action\Exception
 */
class StrategyResolver
{
    /**
     * @var StrategyInterface[]
     */
    private $strategyPool;

    /**
     * @var StrategyInterface
     */
    private $defaultStrategy;

    /**
     * @param DefaultStrategy $default
     * @param array $strategyPool
     */
    public function __construct(
        DefaultStrategy $default,
        array $strategyPool = []
    ) {
        $this->defaultStrategy = $default;
        $this->strategyPool = $strategyPool;
    }

    /**
     * Get strategy for specified exception
     *
     * @param string $paymentMethod
     * @return StrategyInterface
     */
    public function getStrategy($paymentMethod)
    {
        if (isset($this->strategyPool[$paymentMethod])
            && $this->strategyPool[$paymentMethod] instanceof StrategyInterface
        ) {
            return $this->strategyPool[$paymentMethod];
        }
        return $this->defaultStrategy;
    }
}
