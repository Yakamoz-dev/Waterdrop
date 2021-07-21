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
namespace Aheadworks\Sarp2\Engine\DataResolver;

use Aheadworks\Sarp2\Model\Config;
use Magento\Framework\Stdlib\DateTime;

/**
 * Class NextReattemptDate
 * @package Aheadworks\Sarp2\Engine\DataResolver
 */
class NextReattemptDate
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var int
     */
    private $retryInterval;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param DateTime $dateTime
     * @param Config $config
     * @param int $retryInterval
     */
    public function __construct(
        DateTime $dateTime,
        Config $config,
        $retryInterval = 1
    ) {
        $this->dateTime = $dateTime;
        $this->config = $config;
        $this->retryInterval = $retryInterval;
    }

    /**
     * Get next payment reattempt date using current payment date
     *
     * @param string $paymentDate
     * @param int $reattemptsCount
     * @return string
     */
    public function getDateNext($paymentDate)
    {
        $date = new \DateTime($paymentDate);
        $date->modify('+' . $this->retryInterval . ' day');

        return $this->dateTime->formatDate($date);
    }

    /**
     * Get last payment reattempt date
     *
     * @param string $paymentDate
     * @param int $reattemptsCount
     * @return string
     */
    public function getLastDate($paymentDate, $reattemptsCount = 0)
    {
        $remainingReattemptDays = $this->retryInterval * $this->config->getMaxRetriesCount() - $reattemptsCount;

        $date = new \DateTime($paymentDate);
        $date->modify('+' . $remainingReattemptDays . ' day');

        return $this->dateTime->formatDate($date);
    }
}
