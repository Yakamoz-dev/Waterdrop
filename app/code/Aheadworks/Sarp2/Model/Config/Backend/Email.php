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
namespace Aheadworks\Sarp2\Model\Config\Backend;

use Magento\Framework\App\Config\Value;

/**
 * Class Email
 *
 * @package Aheadworks\Sarp2\Model\Config\Backend
 */
class Email extends Value
{
    /**
     * @inheritDoc
     */
    public function beforeSave()
    {
        $value = (string)$this->getValue();
        $value = $this->prepareValue($value);
        $this->setValue($value);
    }

    /**
     * Prepare email value
     *
     * @param string $value
     * @return string
     */
    private function prepareValue($value)
    {
        $valueAsArray = explode(',', $value);

        $prepared = [];
        foreach ($valueAsArray as $email) {
            $email = trim($email);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $prepared[] = $email;
            }
        }

        return implode(',', $prepared);
    }
}
