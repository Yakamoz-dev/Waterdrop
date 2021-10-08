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
namespace Aheadworks\Sarp2\Model\Sales\Order\Item\Option\Processor;

use Magento\Sales\Model\Order\Item as OrderItem;

/**
 * Interface ProcessorInterface
 *
 * @package Aheadworks\Sarp2\Model\Sales\Order\Item\Option\Processor
 */
interface ProcessorInterface
{
    /**
     * Process order item product options
     *
     * @param OrderItem $item
     * @param array $options
     * @return array
     */
    public function process(OrderItem $item, array $options);
}
