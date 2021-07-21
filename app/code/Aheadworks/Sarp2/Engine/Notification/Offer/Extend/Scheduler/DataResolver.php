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
namespace Aheadworks\Sarp2\Engine\Notification\Offer\Extend\Scheduler;

use Aheadworks\Sarp2\Engine\Notification\Offer\Extend\LinkBuilder;
use Aheadworks\Sarp2\Model\Plan\Resolver\TitleResolver;

/**
 * Class DataResolver
 *
 * @package Aheadworks\Sarp2\Engine\Notification\Offer\Extend\Scheduler
 */
class DataResolver
{
    /**
     * @var TitleResolver
     */
    private $titleResolver;

    /**
     * @var LinkBuilder
     */
    private $extendUrlBuilder;

    /**
     * @param TitleResolver $titleResolver
     * @param LinkBuilder $urlBuilder
     */
    public function __construct(
        TitleResolver $titleResolver,
        LinkBuilder $urlBuilder
    ) {
        $this->titleResolver = $titleResolver;
        $this->extendUrlBuilder = $urlBuilder;
    }

    /**
     * Resolve notification data
     *
     * @param DataResolver\ResolveSubject $subject
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resolve(DataResolver\ResolveSubject $subject)
    {
        $profile = $subject->getProfile();
        $data = [
            'extendLink' => $this->extendUrlBuilder->build($profile),
            'customerName' => $profile->getCustomerFullname(),
            'profileId' => $profile->getProfileId(),
            'incrementProfileId' => $profile->getIncrementId(),
            'planName' => $this->titleResolver->getTitle($profile->getPlanId(), $profile->getStoreId())
        ];

        return $data;
    }
}
