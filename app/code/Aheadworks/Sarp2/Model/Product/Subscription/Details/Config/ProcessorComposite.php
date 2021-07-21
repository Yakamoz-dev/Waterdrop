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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Details\Config;

/**
 * Class ProviderPool
 *
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Details\Config
 */
class ProcessorComposite implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $processors = [];

    /**
     * @param ProcessorInterface[] $processors
     */
    public function __construct(
        array $processors = []
    ) {
        $this->processors = array_merge($this->processors, $processors);
    }

    /**
     * @inheritDoc
     */
    public function process(array $config): array
    {
        foreach ($this->processors as $processor) {
            $config = $processor->process($config);
        }

        return $config;
    }
}
