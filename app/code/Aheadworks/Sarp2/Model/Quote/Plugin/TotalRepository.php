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
namespace Aheadworks\Sarp2\Model\Quote\Plugin;

use Aheadworks\Sarp2\Model\Quote\Total\Modifier;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Api\Data\TotalsInterface;

/**
 * Class TotalRepository
 * @package Aheadworks\Sarp2\Model\Quote\Plugin
 */
class TotalRepository
{
    /**
     * @var Modifier
     */
    private $modifier;

    /**
     * @param Modifier $modifier
     */
    public function __construct(Modifier $modifier)
    {
        $this->modifier = $modifier;
    }

    /**
     * @param CartTotalRepositoryInterface $subject
     * @param TotalsInterface $totals
     * @param int $cartId
     * @return TotalsInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(CartTotalRepositoryInterface $subject, TotalsInterface $totals, $cartId)
    {
        return $this->modifier->modify($totals, $cartId);
    }
}
