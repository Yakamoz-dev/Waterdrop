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
namespace Aheadworks\Sarp2\Model\Payment\Checker;

/**
 * Class OfflinePayment
 * @package Aheadworks\Sarp2\Model\Payment\Checker
 */
class OfflinePayment
{
    /**
     * @var array
     */
    private $allowedMethods;

    /**
     * @param array $allowedMethods
     */
    public function __construct(
        array $allowedMethods = []
    ) {
        $this->allowedMethods = $allowedMethods;
    }

    /**
     * Check
     *
     * @param string $methodCode
     * @return bool
     */
    public function check($methodCode)
    {
        return in_array($methodCode, $this->allowedMethods);
    }
}
