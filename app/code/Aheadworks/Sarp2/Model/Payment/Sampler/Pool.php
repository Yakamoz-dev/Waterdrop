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
namespace Aheadworks\Sarp2\Model\Payment\Sampler;

use Aheadworks\Sarp2\Model\Payment\SamplerInterface;

/**
 * Class Pool
 * @package Aheadworks\Sarp2\Model\Payment\Sampler
 */
class Pool
{
    /**
     * @var SamplerInterface[]
     */
    private $instances = [];

    /**
     * @var array
     */
    private $samplers = [];

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param Factory $factory
     * @param array $samplers
     */
    public function __construct(
        Factory $factory,
        array $samplers = []
    ) {
        $this->factory = $factory;
        $this->samplers = array_merge($this->samplers, $samplers);
    }

    /**
     * Get payment method sampler instance
     *
     * @param string $methodCode
     * @return SamplerInterface
     */
    public function getSampler($methodCode)
    {
        if (!isset($this->instances[$methodCode])) {
            if (!isset($this->samplers[$methodCode])) {
                throw new \InvalidArgumentException(
                    sprintf('Unknown payment sampler: %s requested', $methodCode)
                );
            }
            $this->instances[$methodCode] = $this->factory->create($this->samplers[$methodCode]);
        }
        return $this->instances[$methodCode];
    }
}
