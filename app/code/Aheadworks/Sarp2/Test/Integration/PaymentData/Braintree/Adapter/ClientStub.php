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
namespace Aheadworks\Sarp2\Test\Integration\PaymentData\Braintree\Adapter;

use Aheadworks\Sarp2\PaymentData\Braintree\Adapter\Client;
use Braintree\Customer;
use Magento\Payment\Gateway\Http\TransferInterface;

/**
 * Class ClientStub
 * @package Aheadworks\Sarp2\Test\Integration\PaymentData\Braintree\Adapter
 */
class ClientStub extends Client
{
    /**
     * Gateway token
     */
    const TOKEN = 'braintree_token';

    /**
     * {@inheritdoc}
     */
    public function createCustomer(TransferInterface $transfer)
    {
        $customerData = include __DIR__ . '/_files/customer_data.php';
        return ['object' => Customer::factory($customerData)];
    }
}
