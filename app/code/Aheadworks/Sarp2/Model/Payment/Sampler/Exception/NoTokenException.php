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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\Exception;

use Aheadworks\Sarp2\Model\Payment\Sampler\Info;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

/**
 * Class NoTokenException
 *
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Exception
 */
class NoTokenException extends LocalizedException
{
    /**
     * @var Info
     */
    private $samplerInfo;

    /**
     * @inheritDoc
     * @param Phrase $phrase
     * @param Info $samplerInfo
     * @param \Exception|null $cause
     * @param int $code
     */
    public function __construct(Phrase $phrase, Info $samplerInfo, \Exception $cause = null, $code = 0) {
        parent::__construct($phrase, $cause, $code);
        $this->samplerInfo = $samplerInfo;
    }

    /**
     * @return Info
     */
    public function getSamplerInfo(): Info
    {
        return $this->samplerInfo;
    }
}
