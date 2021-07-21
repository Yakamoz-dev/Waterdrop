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
namespace Aheadworks\Sarp2\Model\ResourceModel\Profile\Handler;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;

/**
 * Interface HandlerInterface
 * @package Aheadworks\Sarp2\Model\ResourceModel\Profile
 */
interface HandlerInterface
{
    /**
     * Process profile saving/deletion
     *
     * @param ProfileInterface $profile
     * @return void
     */
    public function process(ProfileInterface $profile);
}
