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
namespace Aheadworks\Sarp2\Engine\Payment\Engine\Iteration;

/**
 * Class State
 * @package Aheadworks\Sarp2\Engine\Payment\Engine\Iteration
 */
class State implements StateInterface
{
    /**
     * @var int
     */
    private $storeId;

    /**
     * @var string
     */
    private $paymentType;

    /**
     * @var int
     */
    private $tmzOffset;

    /**
     * @param int $storeId
     * @param string $paymentType
     * @param int $tmzOffset
     */
    public function __construct(
        $storeId,
        $paymentType,
        $tmzOffset
    ) {
        $this->storeId = $storeId;
        $this->paymentType = $paymentType;
        $this->tmzOffset = $tmzOffset;
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimezoneOffset()
    {
        return $this->tmzOffset;
    }
}
