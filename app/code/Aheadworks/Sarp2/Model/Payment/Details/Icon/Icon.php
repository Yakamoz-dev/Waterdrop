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
namespace Aheadworks\Sarp2\Model\Payment\Details\Icon;

/**
 * Class Icon
 *
 * @package Aheadworks\Sarp2\Model\Payment\Details\Icon
 */
class Icon implements IconInterface
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * Icon constructor.
     *
     * @param string $url
     * @param int $width
     * @param int $height
     */
    public function __construct(string $url, int $width, int $height)
    {
        $this->url = $url;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Retrieve icon url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Retrieve icon width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Retrieve icon height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
}
