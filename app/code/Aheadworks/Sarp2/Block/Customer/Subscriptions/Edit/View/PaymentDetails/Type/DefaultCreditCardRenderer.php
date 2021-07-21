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
namespace Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails\Type;

use Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails\AbstractCreditCardRenderer;

/**
 * Class BaseCreditCardRenderer
 *
 * @package Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails\Type
 */
class DefaultCreditCardRenderer extends AbstractCreditCardRenderer
{
    /**
     * @var string
     */
    protected $_template = 'Aheadworks_Sarp2::customer/subscriptions/edit/view/payment_details/type/credit_card.phtml';
    
    /**
     * {@inheritdoc}
     */
    public function getCreditCardNumber($tokenDetails)
    {
        return isset($tokenDetails['lastCcNumber']) ? $tokenDetails['lastCcNumber'] : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getExpirationDate($tokenDetails)
    {
        return isset($tokenDetails['expirationDate']) ? $tokenDetails['expirationDate'] : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getCreditCardType($tokenDetails)
    {
        return isset($tokenDetails['typeCode']) ? $tokenDetails['typeCode'] : '';
    }
}
