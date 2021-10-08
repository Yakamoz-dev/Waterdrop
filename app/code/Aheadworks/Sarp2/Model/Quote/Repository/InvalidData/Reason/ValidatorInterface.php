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
namespace Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Reason;

use Magento\Quote\Model\Quote;

/**
 * Interface ValidatorInterface
 * @package Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Reason
 */
interface ValidatorInterface
{
    /**
     * Validate quote
     *
     * @param Quote $quote
     * @return bool
     */
    public function validate($quote);

    /**
     * Get invalid data reason
     *
     * @return int
     */
    public function getReason();

    /**
     * Get error message
     *
     * @return string
     */
    public function getErrorMessage();
}
