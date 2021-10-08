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
namespace Aheadworks\Sarp2\Engine\Payment\Engine\Iteration;

/**
 * Interface StateInterface
 * @package Aheadworks\Sarp2\Engine\Payment\Engine\Iteration
 */
interface StateInterface
{
    /**
     * Get store Id
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Get payment type
     *
     * @return string
     */
    public function getPaymentType();

    /**
     * Get timezone offset
     *
     * @return int
     */
    public function getTimezoneOffset();
}
