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
namespace Aheadworks\Sarp2\Model\Quote\Substitute\Quote;

use Magento\Quote\Model\Quote\Address as QuoteAddress;

/**
 * Class Address
 * @package Aheadworks\Sarp2\Model\Quote\Substitute\Quote
 */
class Address extends QuoteAddress
{
    /**
     * {@inheritdoc}
     */
    public function getAllItems()
    {
        return $this->getData('all_items');
    }

    /**
     * {@inheritdoc}
     */
    public function getQuote()
    {
        return $this->getData('quote');
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseSubtotalWithDiscount()
    {
        return $this->getData('base_subtotal_with_discount');
    }
}
