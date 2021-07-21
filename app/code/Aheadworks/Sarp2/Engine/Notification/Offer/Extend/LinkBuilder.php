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
namespace Aheadworks\Sarp2\Engine\Notification\Offer\Extend;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Controller\Profile\Guest\Extend;
use Aheadworks\Sarp2\Model\Access\Management as TokenManagement;
use Magento\Framework\Url as FrontendUrl;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class LinkBuilder
 *
 * @package Aheadworks\Sarp2\Engine\Notification\Offer\Extend
 */
class LinkBuilder
{
    /**
     * @var FrontendUrl
     */
    private $urlBuilder;

    /**
     * @var TokenManagement
     */
    private $tokenManagement;

    /**
     * Store manager
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param FrontendUrl $urlBuilder
     * @param TokenManagement $tokenManagement
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        FrontendUrl $urlBuilder,
        TokenManagement $tokenManagement,
        StoreManagerInterface $storeManager
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->tokenManagement = $tokenManagement;
        $this->storeManager = $storeManager;
    }

    /**
     * Build extend link url
     *
     * @param ProfileInterface $profile
     * @return string|null
     */
    public function build(ProfileInterface $profile)
    {
        try {
            $token = $this->tokenManagement->createToken($profile, Extend::RESOURCE);
            $store = $this->getStore($profile);

            return $this->urlBuilder
                ->setScope($store)
                ->getUrl(
                'aw_sarp2/profile_guest/extend',
                ['token' => $token->getTokenValue()]
            );
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Get store
     *
     * @param ProfileInterface $profile
     * @return StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStore($profile) {
        $storeId = $profile->getStoreId();

        return $this->storeManager->getStore($storeId);
    }
}
