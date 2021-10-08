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
use Magento\Framework\ObjectManagerInterface;

/**
 * Class Factory
 * @package Aheadworks\Sarp2\Model\Payment\Sampler
 */
class Factory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create payment sampler instance
     *
     * @param string $className
     * @return SamplerInterface
     */
    public function create($className)
    {
        $instance = $this->objectManager->create($className);
        if (!$instance instanceof SamplerInterface) {
            throw new \InvalidArgumentException(
                $className . ' doesn\'t implement ' . SamplerInterface::class
            );
        }
        return $instance;
    }
}
