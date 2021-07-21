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
namespace Aheadworks\Sarp2\Model\Access;

use Aheadworks\Sarp2\Api\Data\AccessTokenInterface;
use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class Management
 *
 * @package Aheadworks\Sarp2\Model\Access
 */
class Management
{
    /**
     * @var TokenFactory
     */
    private $tokenFactory;

    /**
     * @var TokenRepository
     */
    private $tokenRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * Management constructor.
     *
     * @param TokenFactory $tokenFactory
     * @param TokenRepository $tokenRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        TokenFactory $tokenFactory,
        TokenRepository $tokenRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->tokenFactory = $tokenFactory;
        $this->tokenRepository = $tokenRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Create new or retrieve existing token for profile and resource
     *
     * @param ProfileInterface $profile
     * @param string $resource
     * @return AccessTokenInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function createToken(ProfileInterface $profile, string $resource)
    {
        $this->searchCriteriaBuilder
            ->addFilter(AccessTokenInterface::PROFILE_ID, $profile->getProfileId())
            ->addFilter(AccessTokenInterface::ALLOWED_RESOURCE, $resource);

        $result = $this->tokenRepository->getList($this->searchCriteriaBuilder->create());
        if ($result->getTotalCount() > 0) {
            $token = $result->getItems()[0];
        } else {
            $token = $this->tokenFactory->create($profile);
            $token->setAllowedResource($resource);
            $this->tokenRepository->save($token);
        }

        return $token;
    }
}
