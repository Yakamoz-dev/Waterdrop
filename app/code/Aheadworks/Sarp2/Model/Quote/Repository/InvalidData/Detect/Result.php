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
namespace Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Detect;

/**
 * Class Result
 * @package Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Detect
 */
class Result implements ResultInterface
{
    /**
     * @var bool
     */
    private $isInvalid;

    /**
     * @var int|null
     */
    private $reason;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @param bool $isInvalid
     * @param int|null $reason
     * @param string $errorMessage
     */
    public function __construct(
        $isInvalid,
        $reason = null,
        $errorMessage = ''
    ) {
        $this->isInvalid = $isInvalid;
        $this->reason = $reason;
        $this->errorMessage = $errorMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function isInvalid()
    {
        return $this->isInvalid;
    }

    /**
     * {@inheritdoc}
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
