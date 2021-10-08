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
namespace Aheadworks\Sarp2\PaymentData\Nmi\Transaction;

/**
 * Class ExpirationDate
 *
 * @package Aheadworks\Sarp2\PaymentData\Nmi\Transaction
 */
class ExpirationDate
{
    /**
     * Get formatted credit card expiration date
     *
     * @param \Aheadworks\Nmi\Model\Api\Result\Response $transaction
     * @return string
     * @throws \Exception
     */
    public function getFormatted($transaction)
    {
        $time = sprintf('%s-%s-01 00:00:00', $transaction->getExpiredInYear(), $transaction->getExpiredInMonth());
        return (new \DateTime($time))
            ->add(new \DateInterval('P1M'))
            ->format('Y-m-d 00:00:00');
    }
}
