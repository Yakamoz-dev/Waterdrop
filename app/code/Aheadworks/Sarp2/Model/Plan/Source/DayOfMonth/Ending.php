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
namespace Aheadworks\Sarp2\Model\Plan\Source\DayOfMonth;

/**
 * Class Ending
 * @package Aheadworks\Sarp2\Model\Plan\Source\DayOfMonth
 */
class Ending
{
    /**
     * Get ending for day of month value
     *
     * @param int $value
     * @return \Magento\Framework\Phrase
     */
    public function getEnding($value)
    {
        if ($value == 1) {
            return __('st');
        } elseif ($value == 2) {
            return __('nd');
        } elseif ($value == 3) {
            return __('rd');
        } else {
            return __('th');
        }
    }
}
