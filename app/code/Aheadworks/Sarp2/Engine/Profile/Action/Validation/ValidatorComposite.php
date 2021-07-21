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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Validation;

/**
 * Class ValidatorComposite
 *
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Validation
 */
class ValidatorComposite extends AbstractValidator
{
    /**
     * @var AbstractValidator[]
     */
    private $validators;

    /**
     * @param array $validators
     */
    public function __construct(
        array $validators = []
    ) {
        $this->validators = $validators;
    }

    /**
     * @inheritDoc
     */
    protected function performValidation($profile, $action)
    {
        foreach ($this->validators as $validator) {
            try {
                if (!$validator->isValid($profile, $action)) {
                    $this->addMessages($validator->getMessages());
                }
            } catch (\Exception $exception) {
                $this->addMessages([$exception->getMessage()]);
            }
        }
    }

    /**
     * Retrieve first message
     *
     * @return string
     */
    public function getMessage()
    {
        $messages = $this->getMessages();

        return reset($messages);
    }
}
