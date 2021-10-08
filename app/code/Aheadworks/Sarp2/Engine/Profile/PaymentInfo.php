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
namespace Aheadworks\Sarp2\Engine\Profile;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;

/**
 * Class PaymentInfo
 * @package Aheadworks\Sarp2\Engine\Profile
 */
class PaymentInfo implements PaymentInfoInterface
{
    /**
     * @var ProfileInterface
     */
    private $profile;

    /**
     * @var string
     */
    private $paymentPeriod;

    /**
     * @param ProfileInterface $profile
     * @param string $paymentPeriod
     */
    public function __construct(
        $profile,
        $paymentPeriod
    ) {
        $this->profile = $profile;
        $this->paymentPeriod = $paymentPeriod;
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
    public function getPaymentPeriod()
    {
        return $this->paymentPeriod;
    }
}
