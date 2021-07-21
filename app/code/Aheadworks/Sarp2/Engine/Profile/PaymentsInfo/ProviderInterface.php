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
namespace Aheadworks\Sarp2\Engine\Profile\PaymentsInfo;

use Aheadworks\Sarp2\Api\Data\ScheduledPaymentInfoInterface;

/**
 * Interface ProviderInterface
 * @package Aheadworks\Sarp2\Engine\Profile\PaymentsInfo
 */
interface ProviderInterface
{
    /**
     * Get scheduled payments info
     *
     * @param $profileId
     * @return ScheduledPaymentInfoInterface
     */
    public function getScheduledPaymentsInfo($profileId);
}
