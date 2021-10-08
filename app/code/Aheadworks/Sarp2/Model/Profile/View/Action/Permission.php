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
namespace Aheadworks\Sarp2\Model\Profile\View\Action;

use Aheadworks\Sarp2\Api\Data\ScheduledPaymentInfoInterface;
use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\ValidatorWrapper;
use Aheadworks\Sarp2\Engine\Profile\Checker\PaymentToken as PaymentTokenChecker;
use Aheadworks\Sarp2\Model\Config\CanCancelSubscriptionValueResolver;
use Aheadworks\Sarp2\Model\Profile\Source\Status;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Sarp2\Model\Config;

/**
 * Class Permission
 *
 * @package Aheadworks\Sarp2\Model\Profile\View\Action
 */
class Permission
{
    /**
     * @var ProfileManagementInterface
     */
    private $profileManagement;

    /**
     * @var ProfileRepositoryInterface
     */
    private $profileRepository;
    
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ValidatorWrapper
     */
    private $extendActionValidator;

    /**
     * @var PaymentTokenChecker
     */
    private $profileTokenChecker;

    /**
     * @var CanCancelSubscriptionValueResolver
     */
    private $canCancelConfigValueResolver;

    /**
     * @param ProfileManagementInterface $profileManagement
     * @param ProfileRepositoryInterface $profileRepository
     * @param ProductRepositoryInterface $productRepository
     * @param Config $config
     * @param ValidatorWrapper $extendActionValidator
     * @param PaymentTokenChecker $profileTokenChecker
     * @param CanCancelSubscriptionValueResolver $canCancelSubscriptionValueResolver
     */
    public function __construct(
        ProfileManagementInterface $profileManagement,
        ProfileRepositoryInterface $profileRepository,
        ProductRepositoryInterface $productRepository,
        Config $config,
        ValidatorWrapper $extendActionValidator,
        PaymentTokenChecker $profileTokenChecker,
        CanCancelSubscriptionValueResolver $canCancelSubscriptionValueResolver
    ) {
        $this->profileManagement = $profileManagement;
        $this->profileRepository = $profileRepository;
        $this->productRepository = $productRepository;
        $this->config = $config;
        $this->extendActionValidator = $extendActionValidator;
        $this->profileTokenChecker = $profileTokenChecker;
        $this->canCancelConfigValueResolver = $canCancelSubscriptionValueResolver;
    }

    /**
     * Check if cancel status available
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isCancelStatusAvailable($profileId)
    {
        $allowedStatuses = $this->profileManagement->getAllowedStatuses($profileId);

        return in_array(Status::CANCELLED, $allowedStatuses);
    }

    /**
     * Check if cancel action available for customer
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isCancelActionAvailableForCustomer($profileId)
    {
        $profile = $this->profileRepository->get($profileId);
        $allowCancellationAction = $this->canCancelConfigValueResolver
            ->canCancelSubscription($profile->getProfileDefinition());

        return $allowCancellationAction && $this->isCancelStatusAvailable($profileId);
    }

    /**
     * Check if cancel action available for admin
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isCancelActionAvailableForAdmin($profileId)
    {
        return $this->isCancelStatusAvailable($profileId);
    }

    /**
     * Check if cancel action available on period holder
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isCancelActionAvailableOnPeriodHolder($profileId)
    {
        $isAvailable = $this->isCancelStatusAvailable($profileId);

        if ($isAvailable) {
            $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profileId);

            $isAvailable = $nextPaymentInfo->getPaymentStatus()
                != ScheduledPaymentInfoInterface::PAYMENT_STATUS_LAST_PERIOD_HOLDER;
        }

        return $isAvailable;
    }

    /**
     * Check if edit action available
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isEditActionAvailable($profileId)
    {
        return $this->isCancelStatusAvailable($profileId);
    }

    /**
     * Check if extend action available
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isExtendActionAvailable($profileId)
    {
        $profile = $this->profileRepository->get($profileId);
        return $this->extendActionValidator->isValid($profile);
    }

    /**
     * Check if renew action available
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isRenewActionAvailable($profileId)
    {
        $profile = $this->profileRepository->get($profileId);
        $isAvailable = $profile->getStatus() == Status::CANCELLED;
        $allowCancellationAction = $this->canCancelConfigValueResolver
            ->canCancelSubscription($profile->getProfileDefinition());

        return $isAvailable && $this->profileManagement->isAllowedToReactivate($profileId) && $allowCancellationAction;
    }

    /**
     * Check if edit product item action is available
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isEditProductItemActionAvailable($profileId)
    {
        return $this->isCancelActionAvailableOnPeriodHolder($profileId)
                && $this->config->canEditProductItem();
    }

    /**
     * Check if edit plan action is available
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isEditPlanActionAvailable($profileId)
    {
        $profile = $this->profileRepository->get($profileId);
        return $this->isCancelStatusAvailable($profileId)
            && $this->config->canSwitchToAnotherPlan()
            && $profile->getStatus() !== Status::PENDING;
    }

    /**
     * Check if edit next payment date action is available
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isEditNextPaymentDateActionAvailable($profileId)
    {
        $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profileId);
        $nextPaymentStatus = $nextPaymentInfo->getPaymentStatus();

        return $this->isCancelStatusAvailable($profileId)
            && $this->config->canEditNextPaymentDate()
            && $nextPaymentStatus != ScheduledPaymentInfoInterface::PAYMENT_STATUS_REATTEMPT
            && $this->isEditNextPaymentDateAllowedForMembership($profileId)
            && $this->profileTokenChecker->check($profileId);
    }

    /**
     * Check if edit address action is available
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isEditAddressActionAvailable($profileId)
    {
        $profile = $this->profileRepository->get($profileId);
        return $this->isEditActionAvailable($profile->getProfileId())
            && !$profile->getIsVirtual()
            && $this->config->canChangeSubscriptionAddress();
    }

    /**
     * Check if edit next payment date is allowed for membership
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    private function isEditNextPaymentDateAllowedForMembership($profileId)
    {
        $profile = $this->profileRepository->get($profileId);
        $isMembershipEnabled = $profile->getProfileDefinition()->getIsMembershipModelEnabled();

        return $isMembershipEnabled ? $this->config->canEditNextPaymentDateForMembership() : true;
    }
}
