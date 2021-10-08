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
namespace Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails\Type;

/**
 * Class AuthorizenetAcceptjs
 * @package Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails\Type
 */
class AuthorizenetAcceptjs extends DefaultCreditCardRenderer
{
    /**
     * {@inheritdoc}
     */
    public function getCreditCardNumber($tokenDetails)
    {
        return isset($tokenDetails['ccLast4']) ? $tokenDetails['ccLast4'] : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getExpirationDate($tokenDetails)
    {
        return 'XXXX';
    }

    /**
     * {@inheritdoc}
     */
    public function getCreditCardType($tokenDetails)
    {
        return isset($tokenDetails['accountType']) ? $tokenDetails['accountType'] : '';
    }
}
