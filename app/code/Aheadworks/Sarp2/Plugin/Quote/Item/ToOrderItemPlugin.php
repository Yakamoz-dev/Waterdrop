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
namespace Aheadworks\Sarp2\Plugin\Quote\Item;

use Aheadworks\Sarp2\Model\Sales\Order\Item\Option\Processor\BundleOptionPriceProcessor;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class ToOrderItemPlugin
 *
 * @package Aheadworks\Sarp2\Plugin\Quote\Item
 */
class ToOrderItemPlugin
{
    /**
     * @var BundleOptionPriceProcessor
     */
    private $bundleOrderOptionsProcessor;

    /**
     * ToOrderItemPlugin constructor.
     *
     * @param BundleOptionPriceProcessor $bundleOrderOptionsProcessor
     */
    public function __construct(BundleOptionPriceProcessor $bundleOrderOptionsProcessor)
    {
        $this->bundleOrderOptionsProcessor = $bundleOrderOptionsProcessor;
    }

    /**
     * Process bundle attributes to order data
     *
     * @param ToOrderItem $subject
     * @param OrderItemInterface $orderItem
     * @param AbstractItem $item
     * @param array $data
     * @return OrderItemInterface
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterConvert(ToOrderItem $subject, OrderItemInterface $orderItem, AbstractItem $item, $data = [])
    {
        $options = $orderItem->getProductOptions();
        $options = $this->bundleOrderOptionsProcessor->process($options);
        $orderItem->setProductOptions($options);

        return $orderItem;
    }
}
