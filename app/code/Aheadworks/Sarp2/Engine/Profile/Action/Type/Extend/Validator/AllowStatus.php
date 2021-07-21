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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\Validator;

use Aheadworks\Sarp2\Model\Profile\Source\Status as StatusSource;
use Aheadworks\Sarp2\Engine\Profile\Action\Validation\AbstractValidator;

/**
 * Class StatusValidator
 *
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\Validator
 */
class AllowStatus extends AbstractValidator
{
    /**
     * @var array
     */
    private $allowStatuses = [
        StatusSource::ACTIVE,
        StatusSource::EXPIRED
    ];

    /**
     * @param array $allowStatuses
     */
    public function __construct(
        array $allowStatuses = []
    ) {
        $this->allowStatuses = array_merge($this->allowStatuses, $allowStatuses);
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function performValidation($profile, $action)
    {
        if (!in_array($profile->getStatus(), $this->allowStatuses)) {
            $this->addMessages(['The subscription status in not correct for perform extend action.']);
        }
    }
}
