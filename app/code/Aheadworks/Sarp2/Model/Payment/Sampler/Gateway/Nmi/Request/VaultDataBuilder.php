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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\Nmi\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class VaultDataBuilder
 *
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\Nmi\Request
 */
class VaultDataBuilder implements BuilderInterface
{
    /**
     * Add/Update a secure customer vault record
     */
    const CUSTOMER_VAULT = 'customer_vault';

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        $result = [
            self::CUSTOMER_VAULT => 'add_customer'
        ];

        return $result;
    }
}
