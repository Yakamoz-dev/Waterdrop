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
 * Interface PaymentDataObjectInterface
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\Data
 */
interface PaymentDataObjectInterface
{
    /**
     * Retrieve profile
     *
     * @return ProfileInterface
     */
    public function getProfile();

    /**
     * Retrieve payment
     *
     * @return SamplerInfoInterface
     */
    public function getPayment();
}
