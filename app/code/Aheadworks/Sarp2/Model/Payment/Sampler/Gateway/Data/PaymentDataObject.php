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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\Data;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Model\Payment\SamplerInfoInterface;

/**
 * Class PaymentDataObject
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\Data
 */
class PaymentDataObject implements PaymentDataObjectInterface
{
    /**
     * @var ProfileInterface
     */
    private $profile;

    /**
     * @var SamplerInfoInterface
     */
    private $payment;

    /**
     * @param ProfileInterface $profile
     * @param SamplerInfoInterface $payment
     */
    public function __construct(
        ProfileInterface $profile,
        SamplerInfoInterface $payment
    ) {
        $this->profile = $profile;
        $this->payment = $payment;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
