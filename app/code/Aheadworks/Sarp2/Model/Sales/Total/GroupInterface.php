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
namespace Aheadworks\Sarp2\Model\Sales\Total;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Magento\Quote\Api\Data\CartItemInterface;

/**
 * Interface GroupInterface
 * @package Aheadworks\Sarp2\Model\Sales\Total
 */
interface GroupInterface
{
    /**#@+
     * Total group codes
     */
    const CODE_INITIAL = 'initial';
    const CODE_TRIAL = 'trial';
    const CODE_REGULAR = 'regular';
    /**#@-*/

    /**
     * Get total group code
     *
     * @return string
     */
    public function getCode();

    /**
     * Get item price
     *
     * @param CartItemInterface|ProfileItemInterface $item
     * @param bool $useBaseCurrency
     * @return float
     */
    public function getItemPrice($item, $useBaseCurrency);

    /**
     * Get totals populator
     *
     * @param string $entityType
     * @return PopulatorInterface
     */
    public function getPopulator($entityType);

    /**
     * Get totals provider
     *
     * @return ProviderInterface
     */
    public function getProvider();
}
