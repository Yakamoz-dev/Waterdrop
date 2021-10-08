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
namespace Aheadworks\Sarp2\Model\Sales\Order\Item\Option;

use Aheadworks\Sarp2\Model\Sales\Order\Item\Option\Processor\ProcessorInterface;
use Magento\Sales\Model\Order\Item as OrderItem;

/**
 * Class ProcessorComposite
 *
 * @package Aheadworks\Sarp2\Model\Sales\Order\Item\Option
 */
class ProcessorComposite implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $processors;

    /**
     * ProcessorComposite constructor.
     *
     * @param ProcessorInterface[] $processors
     */
    public function __construct(array $processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * @inheritDoc
     */
    public function process(OrderItem $item, array $options)
    {
        foreach ($this->processors as $processor) {
            $options = $processor->process($item, $options);
        }

        return $options;
    }
}
