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
namespace Aheadworks\Sarp2\Model\Quote\Item\Checker\IsSubscription;

use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;

/**
 * Class Pool
 *
 * @package Aheadworks\Sarp2\Model\Quote\Item\Checker\IsSubscription
 */
class Pool
{
    /**
     * @var CheckerInterface[]
     */
    private $checkerList;

    /**
     * @param CheckerInterface[] $checkerList
     */
    public function __construct(
        array $checkerList = []
    ) {
        $this->checkerList = $checkerList;
    }

    /**
     * Retrieve checker instance for specific item
     *
     * @param ItemInterface $item
     * @return CheckerInterface|null
     */
    public function getCheckerForItem($item)
    {
        $checker = null;
        $itemClass = get_class($item);
        if (isset($this->checkerList[$itemClass])
            && $this->checkerList[$itemClass] instanceof CheckerInterface
        ) {
            $checker = $this->checkerList[$itemClass];
        }
        return $checker;
    }
}
