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
namespace Aheadworks\Sarp2\Engine\Payment\Generator;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;

/**
 * Class Source
 * @package Aheadworks\Sarp2\Engine\Payment\Generator
 */
class Source implements SourceInterface
{
    /**
     * @var ProfileInterface|null
     */
    private $profile;

    /**
     * @var PaymentInterface[]
     */
    private $payments;

    /**
     * @param ProfileInterface|null $profile
     * @param PaymentInterface[] $payments
     */
    public function __construct(
        $profile = null,
        array $payments = []
    ) {
        $this->profile = $profile;
        $this->payments = $payments;
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
    public function getPayments()
    {
        return $this->payments;
    }
}
