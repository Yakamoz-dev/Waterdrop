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
namespace Aheadworks\Sarp2\Engine\Payment\Generator\Type\Reattempt;

/**
 * Class ScheduleResult
 * @package Aheadworks\Sarp2\Engine\Payment\Generator\Type\Reattempt
 */
class ScheduleResult
{
    /**
     * Reattempt types
     */
    const REATTEMPT_TYPE_RETRY = 'retry';
    const REATTEMPT_TYPE_NEXT = 'next';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $date;

    /**
     * @param string $type
     * @param string $date
     */
    public function __construct($type, $date)
    {
        $this->type = $type;
        $this->date = $date;
    }

    /**
     * Get reattempt type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get reattempt date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }
}
