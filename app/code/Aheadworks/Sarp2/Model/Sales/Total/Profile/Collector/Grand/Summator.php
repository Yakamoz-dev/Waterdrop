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
namespace Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector\Grand;

/**
 * Class Summator
 * @package Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector\Grand
 */
class Summator
{
    /**
     * @var array
     */
    private $amounts = [];

    /**
     * Set total amount
     *
     * @param string $code
     * @param float $amount
     * @return void
     */
    public function setAmount($code, $amount)
    {
        $this->amounts[$code] = $amount;
    }

    /**
     * Get totals sum
     *
     * @param string $prefix
     * @return float
     */
    public function getSum($prefix)
    {
        $result = 0;
        foreach ($this->amounts as $code => $amount) {
            $codeParts = explode('_', $code);
            if (count($codeParts) > 1 && $codeParts[0] == $prefix) {
                $result += $amount;
                unset($this->amounts[$code]);
            }
        }
        return $result;
    }
}
