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
namespace Aheadworks\Sarp2\Engine\Payment\Processor\Type\Planned\Bundle;

use Aheadworks\Sarp2\Engine\PaymentInterface;

/**
 * Class GroupResult
 * @package Aheadworks\Sarp2\Engine\Payment\Processor\Type\Planned\Bundle
 */
class GroupResult
{
    /**
     * @var PaymentInterface[]
     */
    private $singlePayments = [];

    /**
     * @var Candidate[]
     */
    private $bundledCandidates = [];

    /**
     * @param array $singlePayments
     * @param array $bundledCandidates
     */
    public function __construct(
        array $singlePayments = [],
        array $bundledCandidates = []
    ) {
        $this->singlePayments = $singlePayments;
        $this->bundledCandidates = $bundledCandidates;
    }

    /**
     * Get single payments
     *
     * @return PaymentInterface[]
     */
    public function getSinglePayments()
    {
        return $this->singlePayments;
    }

    /**
     * Get bundled payment candidates
     *
     * @return Candidate[]
     */
    public function getBundleCandidates()
    {
        return $this->bundledCandidates;
    }
}
