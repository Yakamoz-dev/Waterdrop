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
namespace Aheadworks\Sarp2\Model\Sales\Total\Merged\Collector\Tax;

use Magento\Framework\DataObject;

/**
 * Class Keyer
 * @package Aheadworks\Sarp2\Model\Sales\Total\Merged\Collector\Tax
 */
class Keyer
{
    /**
     * Key item pairs according to item getter call result
     *
     * @param array $pairs
     * @param string $getter
     * @param int $index
     * @return array
     */
    public function keyPairsBy(array $pairs, $getter, $index = 0)
    {
        $result = [];
        foreach ($pairs as $pair) {
            /** @var DataObject $item */
            $item = $pair[$index]->getItem();
            $result[$item->$getter()] = $pair;
        }
        return $result;
    }
}
