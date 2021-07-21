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
 * Class Result
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Validation
 */
class Result implements ResultInterface
{
    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var string
     */
    private $message;

    /**
     * @param bool $isValid
     * @param string $message
     */
    public function __construct($isValid, $message = '')
    {
        $this->isValid = $isValid;
        $this->message = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }
}
