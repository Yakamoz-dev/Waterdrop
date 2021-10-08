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
namespace Aheadworks\Sarp2Stripe\Api;

/**
 * Interface SetupIntentManagementInterface
 *
 * @package Aheadworks\Sarp2Stripe\Api
 */
interface SetupIntentManagementInterface
{
    /**
     * Create setup intent for quote
     *
     * @param string $email
     * @return string
     */
    public function createForQuote($email);

    /**
     * Create setup intent for profile
     *
     * @param string $profile_id
     * @return string
     */
    public function createForProfile($profile_id);
}
