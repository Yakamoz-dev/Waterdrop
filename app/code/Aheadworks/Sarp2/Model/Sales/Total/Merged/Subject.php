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
namespace Aheadworks\Sarp2\Model\Sales\Total\Merged;

use Aheadworks\Sarp2\Engine\Profile\PaymentInfoInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Class Subject
 * @package Aheadworks\Sarp2\Model\Sales\Total\Merged
 */
class Subject
{
    /**
     * @var PaymentInfoInterface[]
     */
    private $paymentsInfo;

    /**
     * @var OrderInterface
     */
    private $order;

    /**
     * @var array
     */
    private $itemPairs;

    /**
     * @param OrderInterface $order
     * @param PaymentInfoInterface[] $paymentsInfo
     * @param array $itemPairs
     */
    public function __construct(
        $order,
        array $paymentsInfo,
        array $itemPairs
    ) {
        $this->order = $order;
        $this->paymentsInfo = $paymentsInfo;
        $this->itemPairs = $itemPairs;
    }

    /**
     * Get order
     *
     * @return OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Get profiles payments info
     *
     * @return PaymentInfoInterface[]
     */
    public function getPaymentsInfo()
    {
        return $this->paymentsInfo;
    }

    /**
     * Get 'profile item - order item' pairs
     *
     * @return array
     */
    public function getItemPairs()
    {
        return $this->itemPairs;
    }
}
