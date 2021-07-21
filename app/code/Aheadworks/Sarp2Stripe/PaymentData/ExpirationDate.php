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
namespace Aheadworks\Sarp2Stripe\PaymentData;

/**
 * Class ExpirationDate
 * @package Aheadworks\Sarp2Stripe\PaymentData
 */
class ExpirationDate
{
    /**
     * Get formatted credit card expiration date
     *
     * @param string $expMonth
     * @param string $expYear
     * @return string
     */
    public function getFormatted($expMonth, $expYear)
    {
        try {
            $time = sprintf('%s-%s-01 00:00:00', $expYear, $expMonth);
            $formattedDate = (new \DateTime($time))
                ->add(new \DateInterval('P1M'))
                ->format('Y-m-d 00:00:00');
        } catch (\Exception $e) {
            $formattedDate = '';
        }

        return $formattedDate;
    }
}
