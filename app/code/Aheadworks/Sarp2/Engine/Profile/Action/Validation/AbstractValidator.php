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

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\Profile\ActionInterface;

/**
 * Class AbstractValidator
 *
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Validation
 */
abstract class AbstractValidator
{
    /**
     * Array of validation failure messages
     *
     * @var string[]
     */
    protected $messages;

    /**
     * Is valid
     *
     * @param ProfileInterface $profile
     * @param ActionInterface $action
     * @return bool
     */
    public function isValid($profile, $action)
    {
        $this->clearMessages();
        $this->performValidation($profile, $action);

        return !$this->hasMessages();
    }

    /**
     * Perform validation
     *
     * @param ProfileInterface $profile
     * @param ActionInterface $action
     * @return void
     */
    abstract protected function performValidation($profile, $action);

    /**
     * Get validation failure messages
     *
     * @return string[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Whether it has failure messages
     *
     * @return bool
     */
    public function hasMessages()
    {
        return !empty($this->messages);
    }

    /**
     * Clear messages
     *
     * @return void
     */
    protected function clearMessages()
    {
        $this->messages = [];
    }

    /**
     * Add messages
     *
     * @param string[] $messages
     * @return void
     */
    protected function addMessages($messages)
    {
        $this->messages = array_merge_recursive($this->messages, $messages);
    }
}
