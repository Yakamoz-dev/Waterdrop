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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\BamboraApac\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class PurchaseTrnDataBuilder
 *
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\BamboraApac\Request
 */
class PurchaseTrnDataBuilder implements BuilderInterface
{
    /**#@+
     * Transaction block names
     */
    const TRANSACTION_TYPE = 'TrnType';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function build(array $buildSubject)
    {
        return [
            self::TRANSACTION_TYPE => 1 // Credit Card - Purchase
        ];
    }
}
