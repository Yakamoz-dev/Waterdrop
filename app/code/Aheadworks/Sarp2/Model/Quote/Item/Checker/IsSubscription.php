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
namespace Aheadworks\Sarp2\Model\Quote\Item\Checker;

use Aheadworks\Sarp2\Model\Product\Checker\IsSubscription as ProductChecker;
use Aheadworks\Sarp2\Model\Quote\Item\Checker\IsSubscription\CheckerInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;

/**
 * Class IsSubscription
 * @package Aheadworks\Sarp2\Model\Quote\Item\Checker
 */
class IsSubscription implements CheckerInterface
{
    /**
     * @var ProductChecker
     */
    private $productChecker;

    /**
     * @param ProductChecker $productChecker
     */
    public function __construct(ProductChecker $productChecker)
    {
        $this->productChecker = $productChecker;
    }

    /**
     * @inheritDoc
     * @param QuoteItem $item
     */
    public function check($item)
    {
        $product = $item->getParentItem() ? $item->getParentItem()->getProduct() : $item->getProduct();
        if ($this->productChecker->check($product)) {
            $optionId = $item->getOptionByCode('aw_sarp2_subscription_type');
            return $optionId && $optionId->getValue();
        }
        return false;
    }
}
