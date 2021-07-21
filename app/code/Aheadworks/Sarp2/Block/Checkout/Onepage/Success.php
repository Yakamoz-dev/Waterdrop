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
namespace Aheadworks\Sarp2\Block\Checkout\Onepage;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Aheadworks\Sarp2\Model\UrlBuilder;
use Magento\Checkout\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order\Config;

/**
 * Class Success
 *
 * @method bool getCanViewProfiles()
 *
 * @package Aheadworks\Sarp2\Block\Checkout\Onepage
 */
class Success extends \Magento\Checkout\Block\Onepage\Success
{
    /**
     * @var ProfileRepositoryInterface
     */
    private $profileRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * @param Context $context
     * @param Session $checkoutSession
     * @param Config $orderConfig
     * @param HttpContext $httpContext
     * @param ProfileRepositoryInterface $profileRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param UrlBuilder $urlBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $checkoutSession,
        Config $orderConfig,
        HttpContext $httpContext,
        ProfileRepositoryInterface $profileRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        UrlBuilder $urlBuilder,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $checkoutSession,
            $orderConfig,
            $httpContext,
            $data
        );
        $this->profileRepository = $profileRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareBlockData()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
        if ($order->getIncrementId()) {
            parent::prepareBlockData();
        }
        $this->addData(['can_view_profiles' => $this->httpContext->getValue('customer_logged_in')]);
    }

    /**
     * Get profiles
     *
     * @return ProfileInterface[]
     */
    public function getProfiles()
    {
        $profiles = [];
        $profileIds = $this->_checkoutSession->getLastProfileIds();
        if ($profileIds) {
            $this->_checkoutSession->setLastProfileIds(null);
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(ProfileInterface::PROFILE_ID, $profileIds, 'in')
                ->create();
            $profiles = $this->profileRepository->getList($searchCriteria)
                ->getItems();
        }
        return $profiles;
    }

    /**
     * Get view profile url
     *
     * @param ProfileInterface $profile
     * @return string
     */
    public function getViewProfileUrl($profile)
    {
        return $this->urlBuilder->getProfileEditIndexUrl($profile->getProfileId());
    }
}
