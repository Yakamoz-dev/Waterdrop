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

require 'payment_token_rollback.php';
require 'plan_rollback.php';

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Model\Profile;
use Aheadworks\Sarp2\Model\ResourceModel\Profile as ProfileResource;
use Magento\Framework\Registry;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var Registry $registry */
$registry = $objectManager->get(Registry::class);
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/** @var Profile $profile */
$profile = $objectManager->create(Profile::class);
/** @var ProfileResource $profileResource */
$profileResource = $objectManager->create(ProfileResource::class);
$profileResource->load($profile, '000000001', ProfileInterface::INCREMENT_ID);
if ($profile->getProfileId()) {
    $profileResource->delete($profile);
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
