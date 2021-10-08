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
namespace Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Reason\Validator;

use Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Detect\ResultInterface;
use Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Reason\AbstractValidator;

/**
 * Class GiftCards
 * @package Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Reason\Validator
 */
class GiftCards extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    protected $errorMessage = 'Gift Card(s) can not be applied to the cart which contains subscription(s)';

    /**
     * {@inheritdoc}
     */
    public function validate($quote)
    {
        $this->reset();
        if ($quote->getGiftCards()) {
            $this->isValid = false;
            $this->reason = $this->quoteChecker->checkHasBoth($quote)
                ? ResultInterface::REASON_EE_GIFT_CARD_ON_MIXED_CART
                : ResultInterface::REASON_EE_GIFT_CARD_ON_SUBSCRIPTION_CART;
        }
        return $this->isValid;
    }
}
