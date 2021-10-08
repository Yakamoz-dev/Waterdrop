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
namespace Aheadworks\Sarp2\Engine\Payment\Processor\Process;

/**
 * Class Result
 * @package Aheadworks\Sarp2\Engine\Payment\Processor\Process
 */
class Result implements ResultInterface
{
    /**
     * @var bool
     */
    private $isOutstandingDetected;

    /**
     * @param bool $isOutstandingDetected
     */
    public function __construct($isOutstandingDetected = false)
    {
        $this->isOutstandingDetected = $isOutstandingDetected;
    }

    /**
     * {@inheritdoc}
     */
    public function isOutstandingDetected()
    {
        return $this->isOutstandingDetected;
    }
}
