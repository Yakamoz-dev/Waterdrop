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
namespace Aheadworks\Sarp2\Model\Profile\Item\Merge;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;

/**
 * Class Result
 * @package Aheadworks\Sarp2\Model\Profile\Item\Merge
 */
class Result
{
    /**
     * @var ProfileItemInterface
     */
    private $item;

    /**
     * @var string
     */
    private $paymentPeriod;

    /**
     * @param ProfileItemInterface $item
     * @param string $paymentPeriod
     */
    public function __construct(
        $item,
        $paymentPeriod
    ) {
        $this->item = $item;
        $this->paymentPeriod = $paymentPeriod;
    }

    /**
     * Get profile item
     *
     * @return ProfileItemInterface
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Get payment period
     *
     * @return string
     */
    public function getPaymentPeriod()
    {
        return $this->paymentPeriod;
    }
}
