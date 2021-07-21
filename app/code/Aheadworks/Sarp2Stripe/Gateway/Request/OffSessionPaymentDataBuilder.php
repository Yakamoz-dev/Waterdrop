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
namespace Aheadworks\Sarp2Stripe\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class OffSessionPaymentDataBuilder
 *
 * @package Aheadworks\Sarp2Stripe\Gateway\Request
 */
class OffSessionPaymentDataBuilder implements BuilderInterface
{
    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        $result['off_session'] = true;
        $result['confirm'] = true;

        return $result;
    }
}
