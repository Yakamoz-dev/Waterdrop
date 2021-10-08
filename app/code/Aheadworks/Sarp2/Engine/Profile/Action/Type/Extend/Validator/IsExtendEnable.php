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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\Validator;

use Aheadworks\Sarp2\Engine\Profile\Action\Validation\AbstractValidator;

/**
 * Class IsExtendEnable
 *
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\Validator
 */
class IsExtendEnable extends AbstractValidator
{
    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function performValidation($profile, $action)
    {
        $profileDefinition = $profile->getProfileDefinition();
        if (!$profileDefinition->getIsExtendEnable()) {
            $this->addMessages(['The Extend action is not possible for this subscription.']);
        }
    }
}
