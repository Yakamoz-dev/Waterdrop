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
namespace Aheadworks\Sarp2\Model\Quote\Substitute;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote as QuoteModel;

/**
 * Class Quote
 * @package Aheadworks\Sarp2\Model\Quote\Substitute
 */
class Quote extends QuoteModel
{
    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->getData('store_id');
    }

    /**
     * {@inheritdoc}
     */
    public function getStore()
    {
        return $this->getData('store');
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAddress()
    {
        return $this->getData('shipping_address');
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAddress(AddressInterface $address = null)
    {
        return $this->setData('shipping_address', $address);
    }
}
