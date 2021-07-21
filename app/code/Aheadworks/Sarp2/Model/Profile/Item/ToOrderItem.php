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
namespace Aheadworks\Sarp2\Model\Profile\Item;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Aheadworks\Sarp2\Model\Sales\CopySelf;
use Aheadworks\Sarp2\Model\Sales\Order\Item\Option\Processor\BundleOptionPriceProcessor;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\DataObject\Copy;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderItemInterfaceFactory;
use Magento\Sales\Model\Order\Item;
use Magento\Tax\Model\Config as TaxConfig;

/**
 * Class ToOrderItem
 * @package Aheadworks\Sarp2\Model\Profile\Item
 */
class ToOrderItem
{
    /**
     * @var OrderItemInterfaceFactory
     */
    private $orderItemFactory;

    /**
     * @var Copy
     */
    private $objectCopyService;

    /**
     * @var CopySelf
     */
    private $selfCopyService;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var TaxConfig
     */
    private $taxConfig;

    /**
     * @var BundleOptionPriceProcessor
     */
    private $bundleOrderOptionsProcessor;

    /**
     * @var array
     */
    private $selfCopyMapExcludeTax = [
        [OrderItemInterface::PRICE, OrderItemInterface::ORIGINAL_PRICE],
        [OrderItemInterface::BASE_PRICE, OrderItemInterface::BASE_ORIGINAL_PRICE],
        [OrderItemInterface::BASE_PRICE, OrderItemInterface::BASE_COST]
    ];

    /**
     * @var array
     */
    private $selfCopyMapIncludeTax = [
        [OrderItemInterface::PRICE_INCL_TAX, OrderItemInterface::ORIGINAL_PRICE],
        [OrderItemInterface::BASE_PRICE_INCL_TAX, OrderItemInterface::BASE_ORIGINAL_PRICE],
        [OrderItemInterface::BASE_PRICE_INCL_TAX, OrderItemInterface::BASE_COST]
    ];

    /**
     * @param OrderItemInterfaceFactory $orderItemFactory
     * @param Copy $objectCopyService
     * @param CopySelf $selfCopyService
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param TaxConfig $taxConfig
     * @param BundleOptionPriceProcessor $bundleOrderOptionsProcessor
     */
    public function __construct(
        OrderItemInterfaceFactory $orderItemFactory,
        Copy $objectCopyService,
        CopySelf $selfCopyService,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        TaxConfig $taxConfig,
        BundleOptionPriceProcessor $bundleOrderOptionsProcessor
    ) {
        $this->orderItemFactory = $orderItemFactory;
        $this->objectCopyService = $objectCopyService;
        $this->selfCopyService = $selfCopyService;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->taxConfig = $taxConfig;
        $this->bundleOrderOptionsProcessor = $bundleOrderOptionsProcessor;
    }

    /**
     * Convert profile item to order item
     *
     * @param ProfileItemInterface $profileItem
     * @param string $paymentPeriod
     * @param array $data
     * @return OrderItemInterface
     */
    public function convert(ProfileItemInterface $profileItem, $paymentPeriod, $data = [])
    {
        $profileItemClone = clone $profileItem;
        $options = $profileItemClone->getProductOptions();
        if (is_array($options)) {
            $options['aw_sarp2_subscription_payment_period'] = $paymentPeriod;
        } else {
            $options->setData('aw_sarp2_subscription_payment_period', $paymentPeriod);
        }
        $options = $this->bundleOrderOptionsProcessor->process($options);

        $this->dataObjectHelper->populateWithArray(
            $profileItemClone,
            $this->dataObjectProcessor->buildOutputDataArray($profileItemClone, ProfileItemInterface::class),
            ProfileItemInterface::class
        );
        $orderItemData = $this->objectCopyService->getDataFromFieldset(
            'aw_sarp2_convert_profile_item',
            'to_order_item',
            $profileItemClone
        );
        $orderItemData = array_merge(
            $orderItemData,
            $this->objectCopyService->getDataFromFieldset(
                'aw_sarp2_convert_profile_item',
                'to_order_item_' . $paymentPeriod,
                $profileItemClone
            )
        );
        $storeId = $profileItemClone->getStoreId();
        $isPriceIncludesTax = $this->taxConfig->priceIncludesTax($storeId);
        $orderItemData = $isPriceIncludesTax
            ? $this->selfCopyService->copyByMap($orderItemData, $this->selfCopyMapIncludeTax)
            : $this->selfCopyService->copyByMap($orderItemData, $this->selfCopyMapExcludeTax);

        if (!empty($data)) {
            $orderItemData = array_merge($orderItemData, $data);
        }

        /** @var OrderItemInterface|Item $orderItem */
        $orderItem = $this->orderItemFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $orderItem,
            $orderItemData,
            OrderItemInterface::class
        );
        $orderItem
            ->setProductOptions($options)
            ->setDiscountAmount(0.0);

        return $orderItem;
    }
}
