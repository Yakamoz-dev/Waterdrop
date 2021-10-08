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
namespace Aheadworks\Sarp2\Model\Sales\Total\Merged\Collector\Grand;

/**
 * Class Summator
 * @package Aheadworks\Sarp2\Model\Sales\Total\Merged\Collector\Grand
 */
class Summator
{
    /**
     * @var array
     */
    private $totalAmounts = [];

    /**
     * Set total amount
     *
     * @param string $totalCode
     * @param float $amount
     * @return void
     */
    public function setTotalAmount($totalCode, $amount)
    {
        $this->totalAmounts[$totalCode] = $amount;
    }

    /**
     * Get totals sum
     *
     * @return float
     */
    public function getSum()
    {
        $result = 0;
        foreach ($this->totalAmounts as $code => $amount) {
            $result += $amount;
            unset($this->totalAmounts[$code]);
        }
        return $result;
    }
}
