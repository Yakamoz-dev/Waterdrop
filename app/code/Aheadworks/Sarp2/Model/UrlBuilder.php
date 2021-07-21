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
namespace Aheadworks\Sarp2\Model;

use Magento\Framework\UrlInterface;

/**
 * Class UrlBuilder
 *
 * @package Aheadworks\Sarp2\Model
 */
class UrlBuilder
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * UrlBuilder constructor.
     *
     * @param UrlInterface $urlBuilder
     */
    public function __construct(UrlInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Get profile edit index url
     *
     * @param $profileId
     * @return string
     */
    public function getProfileEditIndexUrl($profileId)
    {
        return $this->urlBuilder->getUrl(
            'aw_sarp2/profile_edit/index',
            ['profile_id' => $profileId]
        );
    }
}
