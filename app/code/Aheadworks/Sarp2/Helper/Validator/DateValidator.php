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
namespace Aheadworks\Sarp2\Helper\Validator;

use DateTime;
use DateTimeImmutable;

/**
 * Class DateValidator
 *
 * @package Aheadworks\Sarp2\Helper\Validator
 */
class DateValidator
{
    /**
     * Returns true if $value is a DateTime instance or can be converted into one string.
     *
     * @param mixed $value
     * @param string $format
     * @return bool
     */
    public function isValid($value, $format)
    {
        if ($value instanceof DateTime || $value instanceof DateTimeImmutable) {
            return true;
        }

        if (is_string($value)) {
            DateTime::createFromFormat($format, $value);

            // Invalid dates can show up as warnings (ie. "2007-02-99")
            // and still return a DateTime object.
            $errors = DateTime::getLastErrors();
            if ($errors['warning_count'] > 0 || $errors['error_count'] > 0) {
                return false;
            }

            return true;
        }

        return false;
    }
}
