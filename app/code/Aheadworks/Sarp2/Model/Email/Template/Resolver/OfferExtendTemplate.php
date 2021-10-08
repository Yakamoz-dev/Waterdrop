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
namespace Aheadworks\Sarp2\Model\Email\Template\Resolver;

use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Aheadworks\Sarp2\Engine\NotificationInterface;

/**
 * Class OfferExtendTemplate
 *
 * @package Aheadworks\Sarp2\Model\Email\Template\Resolver
 */
class OfferExtendTemplate implements TemplateResolverInterface
{
    /**
     * @var ProfileRepositoryInterface
     */
    private $profileRepository;

    /**
     * @param ProfileRepositoryInterface $profileRepository
     */
    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * @inheritDoc
     */
    public function resolve(NotificationInterface $notification)
    {
        $profileId = $notification->getProfileId();
        $profile = $this->profileRepository->get($profileId);

        return $profile->getProfileDefinition()->getOfferExtendEmailTemplate();
    }
}
