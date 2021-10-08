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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\Info;

/**
 * Class Amount
 *
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Info
 */
class Amount
{
    /**
     * @var float|int
     */
    private $amount;

    /**
     * @param float|int $amount
     */
    public function __construct(
        $amount = 1
    ) {
        $this->amount = $amount;
    }

    /**
     * Get amount
     *
     * @return float|int
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
