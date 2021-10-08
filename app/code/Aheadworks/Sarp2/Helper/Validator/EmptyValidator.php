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

/**
 * Class NotEmptyValidator
 *
 * @package Aheadworks\Sarp2\Helper\Validator
 */
class EmptyValidator
{
    /**
     * Returns true if $value is empty.
     *
     * "" - true
     * 0 - false
     * 0.0 - false
     * "0" - false
     * 42 - false
     * 1337.0 - false
     * "ab" - false
     * [] - true
     * false - true
     *
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        return empty($value) && !is_numeric($value);
    }
}
