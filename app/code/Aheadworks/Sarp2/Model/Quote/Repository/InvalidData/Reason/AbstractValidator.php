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
namespace Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Reason;

use Aheadworks\Sarp2\Model\Quote\Checker\HasSubscriptions;
use Magento\Quote\Model\Quote;

/**
 * Class AbstractValidator
 * @package Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Reason
 */
abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @var HasSubscriptions
     */
    protected $quoteChecker;

    /**
     * @var bool
     */
    protected $isValid = true;

    /**
     * @var int
     */
    protected $reason;

    /**
     * @var string
     */
    protected $errorMessage = 'Invalid data detected.';

    /**
     * @param HasSubscriptions $quoteChecker
     */
    public function __construct(HasSubscriptions $quoteChecker)
    {
        $this->quoteChecker = $quoteChecker;
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
        return $this->isValid ? '' : $this->errorMessage;
    }

    /**
     * Reset state
     *
     * @return void
     */
    protected function reset()
    {
        $this->isValid = true;
        $this->reason = null;
    }
}
