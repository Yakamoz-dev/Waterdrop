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
namespace Aheadworks\Sarp2\Model\Access\Token;

use Aheadworks\Sarp2\Api\Data\AccessTokenInterface;
use Magento\Framework\Stdlib\DateTime\DateTime as CoreDate;
use Magento\Framework\Validator\AbstractValidator;

/**
 * Class Validator
 *
 * @package Aheadworks\Sarp2\Model\Access\Token
 */
class Validator extends AbstractValidator
{
    /**
     * @var CoreDate
     */
    private $coreDate;

    /**
     * @param CoreDate $coreDate
     */
    public function __construct(CoreDate $coreDate)
    {
        $this->coreDate = $coreDate;
    }

    /**
     * Returns true if access token entity meets the validation requirements
     *
     * @param AccessTokenInterface $token
     * @return bool
     */
    public function isValid($token)
    {
        $this->_clearMessages();

        $now = $this->coreDate->gmtTimestamp();

        if ($token->getCreatedAt()) {
            $created_at = $this->coreDate->gmtTimestamp($token->getCreatedAt());
            if ($now < $created_at) {
                $this->_addMessages(['Token not available.']);
                return false;
            }
        }

        if ($token->getExpiresAt()) {
            $expires_at = $this->coreDate->gmtTimestamp($token->getExpiresAt());
            if ($now > $expires_at) {
                $this->_addMessages(['Token expired.']);
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieve first error message
     *
     * @return string
     */
    public function getMessage()
    {
        $messages = $this->getMessages();
        return reset($messages);
    }
}
