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
 * @package    Sarp2Stripe
 * @version    1.0.5
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Model\Profile\View\Edit\Payment\ConfigProcessor;

use Aheadworks\Sarp2\Model\Profile\View\Edit\Payment\ConfigProcessor\AbstractConfigProcessor;

/**
 * Class VaultCleaner
 *
 * @package Aheadworks\Sarp2Stripe\Model\Profile\View\Edit\Payment\ConfigProcessor
 */
class VaultCleaner extends AbstractConfigProcessor
{
    /**
     * @inheritdoc
     */
    public function process($config)
    {
        $this->setValue($config, 'payment/stripe_payments/savedCards', []);
        $this->setValue($config, 'payment/stripe_payments/showSaveCardOption', false);

        return $config;
    }
}
