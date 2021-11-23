<?php
/**
 * Magedelight
 * Copyright (C) 2019 Magedelight <info@magedelight.com>
 *
 * @category Magedelight
 * @package Magedelight_Bundlediscount
 * @copyright Copyright (c) 2019 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */
namespace Magedelight\Bundlediscount\Plugin;

class Creditmemo
{
    protected $_checkoutSession;

    protected $quoteRepository;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
    }
    public function afterCollectTotals(\Magento\Sales\Model\Order\Creditmemo $subject, $result)
    {
        if($result->getOrder()->getDiscountAmount() > 0) {
            $result->setDiscountAmount($result->getOrder()->getDiscountAmount());
            $result->setGrandTotal($result->getOrder()->getGrandTotal());
        }
        return $result;
    }
}
