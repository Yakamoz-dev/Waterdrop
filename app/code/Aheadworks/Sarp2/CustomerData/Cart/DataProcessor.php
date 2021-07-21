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
namespace Aheadworks\Sarp2\CustomerData\Cart;

use Magento\Checkout\Model\Session;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;

/**
 * Class DataProcessor
 * @package Aheadworks\Sarp2\CustomerData\Cart
 */
class DataProcessor
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var ItemDataProcessor
     */
    private $itemDataProcessor;

    /**
     * @param Session $checkoutSession
     * @param ItemDataProcessor $itemDataProcessor
     */
    public function __construct(
        Session $checkoutSession,
        ItemDataProcessor $itemDataProcessor
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->itemDataProcessor = $itemDataProcessor;
    }

    /**
     * Process customer cart section data
     *
     * @param array $data
     * @return array
     */
    public function process(array $data)
    {
        $quote = $this->checkoutSession->getQuote();
        $updateData = [
            'items' => $this->processItems(
                isset($data['items']) ? $data['items'] : [],
                $quote
            )
        ];
        return array_merge($data, $updateData);
    }

    /**
     * Process quote items data
     *
     * @param array $itemsData
     * @param Quote $quote
     * @return array
     */
    private function processItems(array $itemsData, $quote)
    {
        $allVisibleItems = $quote->getAllVisibleItems();
        foreach ($itemsData as $index => $itemData) {
            if (isset($itemData['item_id'])) {
                $item = $this->getItemById($itemData['item_id'], $allVisibleItems);
                if ($item) {
                    $itemsData[$index] = $this->itemDataProcessor->process($item, $itemData);
                }
            }
        }
        return $itemsData;
    }

    /**
     * Get item by Id
     *
     * @param Item $itemId
     * @param Item[] $items
     * @return Item|null
     */
    private function getItemById($itemId, $items)
    {
        foreach ($items as $item) {
            if ($item->getId() == $itemId) {
                return $item;
            }
        }
        return null;
    }
}
