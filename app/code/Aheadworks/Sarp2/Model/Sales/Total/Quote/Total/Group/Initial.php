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
namespace Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Group;

use Aheadworks\Sarp2\Model\Sales\Total\Group\AbstractGroup;
use Magento\Bundle\Model\Product\Type as BundleType;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Magento\Quote\Model\Quote\Item\AbstractItem;

/**
 * Class Initial
 * @package Aheadworks\Sarp2\Model\Sales\Total\Quote\Total\Group
 */
class Initial extends AbstractGroup
{
    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return self::CODE_INITIAL;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemPrice($item, $useBaseCurrency)
    {
        $result = 0.0;
        $optionId = $item->getOptionByCode('aw_sarp2_subscription_type');
        if ($optionId) {
            $option = $this->optionRepository->get($optionId->getValue());
            $planDefinition = $option->getPlan()->getDefinition();

            $initialFee = $option->getInitialFee();
            $baseItemPrice = $initialFee != 0 && $planDefinition->getIsInitialFeeEnabled()
                ? (float)$initialFee
                : 0;
            $result = $useBaseCurrency
                ? $baseItemPrice
                : $this->priceCurrency->convert($baseItemPrice);

            if ($this->isBundleChild($item)) {
                $childrenCount = count($item->getParentItem()->getChildren());
                $result /= $childrenCount;
                $result /= $item->getQty();
            }
        }

        return $result;
    }

    /**
     * Check is bundle child item
     *
     * @param ItemInterface|AbstractItem $item
     * @return bool
     */
    private function isBundleChild($item)
    {
        return $item->getParentItem() && $item->getParentItem()->getProductType() == BundleType::TYPE_CODE;
    }
}
