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
namespace Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails;

use Aheadworks\Sarp2\Api\Data\PaymentTokenInterface as Sarp2PaymentTokenInterface;

/**
 * Interface TokenRendererInterface
 *
 * @package Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails
 */
interface TokenRendererInterface
{
    /**
     * Renders specified token
     *
     * @param Sarp2PaymentTokenInterface $token
     * @return string
     */
    public function render($token);
}
