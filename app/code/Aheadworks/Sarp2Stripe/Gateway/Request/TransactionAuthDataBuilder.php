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
 * @version    1.0.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class TransactionAuthDataBuilder
 * @package Aheadworks\Sarp2Stripe\Gateway\Request
 */
class TransactionAuthDataBuilder implements BuilderInterface
{
    /**
     * Request field name
     */
    const CAPTURE_METHOD = 'capture_method';

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        return [
            self::CAPTURE_METHOD => 'manual'
        ];
    }
}
