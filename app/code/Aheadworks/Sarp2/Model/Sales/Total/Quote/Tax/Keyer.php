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
namespace Aheadworks\Sarp2\Model\Sales\Total\Quote\Tax;

use Magento\Framework\DataObject;

/**
 * Class Keyer
 * @package Aheadworks\Sarp2\Model\Sales\Total\Quote\Tax
 */
class Keyer
{
    /**
     * Key items according to getter call result
     *
     * @param DataObject[] $items
     * @param string $getter
     * @return DataObject[]
     */
    public function keyBy(array $items, $getter)
    {
        $result = [];
        foreach ($items as $item) {
            $result[$item->$getter()] = $item;
        }
        return $result;
    }
}
