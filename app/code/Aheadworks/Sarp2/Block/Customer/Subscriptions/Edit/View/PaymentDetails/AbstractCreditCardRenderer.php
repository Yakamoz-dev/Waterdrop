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
namespace Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails;

/**
 * Class AbstractCreditCardRenderer
 *
 * @package Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails\Type
 */
abstract class AbstractCreditCardRenderer extends AbstractTokenWithIconRenderer
{
    /**
     * Retrieve truncated credit card number
     *
     * @param array $tokenDetails
     * @return string
     */
    abstract public function getCreditCardNumber($tokenDetails);

    /**
     * Retrieve credit card expiration date
     *
     * @param array $tokenDetails
     * @return string
     */
    abstract public function getExpirationDate($tokenDetails);

    /**
     * Retrieve credit card type
     *
     * @param array $tokenDetails
     * @return string
     */
    abstract public function getCreditCardType($tokenDetails);
}
