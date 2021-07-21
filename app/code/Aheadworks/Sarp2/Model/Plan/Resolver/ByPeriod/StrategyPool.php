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
namespace Aheadworks\Sarp2\Model\Plan\Resolver\ByPeriod;

use Magento\Framework\ObjectManagerInterface;

/**
 * Class StrategyPool
 *
 * @package Aheadworks\Sarp2\Model\Plan\Resolver\ByPeriod
 */
class StrategyPool
{
    const TYPE_INITIAL = 'initial';
    const TYPE_TRIAL = 'trial';
    const TYPE_REGULAR = 'regular';

    /**
     * @var StrategyInterface[]
     */
    private $pool;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Pool constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param StrategyInterface[] $pool
     */
    public function __construct(ObjectManagerInterface $objectManager, array $pool = [])
    {
        $this->objectManager = $objectManager;
        $this->pool = $pool;
    }

    /**
     * Get strategy for resolving plan data
     *
     * @param string $type
     * @return StrategyInterface
     */
    public function getStrategy(string $type)
    {
        if (array_key_exists($type, $this->pool)) {
            if (!$this->pool[$type] instanceof StrategyInterface) {
                $this->pool[$type] = $this->objectManager->create($this->pool[$type]);
            }
            return $this->pool[$type];
        }

        throw new \RuntimeException('Undefined resolving strategy type: ' . $type);
    }
}
