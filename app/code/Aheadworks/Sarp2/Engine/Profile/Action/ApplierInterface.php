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
namespace Aheadworks\Sarp2\Engine\Profile\Action;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\Profile\ActionInterface;
use Aheadworks\Sarp2\Engine\Profile\Action\Validation\ResultInterface;

/**
 * Interface ApplierInterface
 * @package Aheadworks\Sarp2\Engine\Profile\Action
 */
interface ApplierInterface
{
    /**
     * Apply action
     *
     * @param ProfileInterface $profile
     * @param ActionInterface $action
     * @return ProfileInterface
     */
    public function apply(ProfileInterface $profile, ActionInterface $action);

    /**
     * Validate action to be applied on specified profile
     *
     * @param ProfileInterface $profile
     * @param ActionInterface $action
     * @return ResultInterface
     */
    public function validate(ProfileInterface $profile, ActionInterface $action);
}
