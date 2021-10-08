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

use Aheadworks\Sarp2\Model\Quote\Checker\HasSubscriptions;
use Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Detect\ResultInterface;
use Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Reason\AbstractValidator;
use Magento\Framework\Module\Manager;

/**
 * Class AwGiftCards
 * @package Aheadworks\Sarp2\Model\Quote\Repository\InvalidData\Reason\Validator
 */
class AwGiftCards extends AbstractValidator
{
    /**
     * @var Manager
     */
    private $moduleManager;

    /**
     * {@inheritdoc}
     */
    protected $errorMessage = 'Gift Card(s) can not be applied to the cart which contains subscription(s)';

    /**
     * @param HasSubscriptions $quoteChecker
     * @param Manager $moduleManager
     */
    public function __construct(
        HasSubscriptions $quoteChecker,
        Manager $moduleManager
    ) {
        parent::__construct($quoteChecker);
        $this->moduleManager = $moduleManager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($quote)
    {
        $this->reset();
        $extension = $quote->getExtensionAttributes();
        if ($this->moduleManager->isEnabled('Aheadworks_Giftcard')
            && $extension
            && $extension->getAwGiftcardCodes()
        ) {
            $this->isValid = false;
            $this->reason = $this->quoteChecker->checkHasBoth($quote)
                ? ResultInterface::REASON_AW_GIFT_CARD_ON_MIXED_CART
                : ResultInterface::REASON_AW_GIFT_CARD_ON_SUBSCRIPTION_CART;
        }
        return $this->isValid;
    }
}
