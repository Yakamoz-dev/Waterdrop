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
namespace Aheadworks\Sarp2\Model\Sales\Total;

use Magento\Framework\DataObject;

/**
 * Interface PopulatorInterface
 * @package Aheadworks\Sarp2\Model\Sales\Total
 */
interface PopulatorInterface
{
    /**#@+
     * Currency options
     */
    const CURRENCY_OPTION_USE_BASE = 'base';
    const CURRENCY_OPTION_USE_STORE = 'store';
    const CURRENCY_OPTION_CONVERT = 'convert';
    /**#@-*/

    /**
     * Populate entity with totals data
     *
     * @param object $entity
     * @param DataObject $totalsDetails
     * @param string $currencyOption
     * @return void
     */
    public function populate(
        $entity,
        DataObject $totalsDetails,
        $currencyOption = self::CURRENCY_OPTION_CONVERT
    );
}
