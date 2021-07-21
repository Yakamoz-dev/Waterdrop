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
namespace Aheadworks\Sarp2\Block\Customer;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ScheduledPaymentInfoInterface;
use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Aheadworks\Sarp2\Model\Plan\Resolver\TitleResolver;
use Aheadworks\Sarp2\Model\Profile\Source\Status as StatusSource;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Url as ProductUrl;
use Magento\Directory\Model\Currency;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Sarp2\Model\Profile\View\Action\Permission as ActionPermission;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Sarp2\Engine\Profile\Checker\PaymentToken as TokenActiveChecker;

/**
 * Class Subscription
 * @package Aheadworks\Sarp2\Block\Customer
 */
class Subscription extends Template
{
    /**
     * @var ProfileManagementInterface
     */
    protected $profileManagement;

    /**
     * @var StatusSource
     */
    protected $statusSource;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ProductUrl
     */
    protected $productUrl;

    /**
     * @var CurrencyFactory
     */
    protected $currencyFactory;

    /**
     * @var ActionPermission
     */
    protected $actionPermission;

    /**
     * @var PlanRepositoryInterface
     */
    protected $planRepository;

    /**
     * @var TitleResolver
     */
    protected $planTitleResolver;

    /**
     * @var TokenActiveChecker
     */
    protected $profileTokenChecker;

    /**
     * @param Context $context
     * @param ProfileManagementInterface $profileManagement
     * @param StatusSource $statusSource
     * @param ProductRepositoryInterface $productRepository
     * @param ProductUrl $productUrl
     * @param CurrencyFactory $currencyFactory
     * @param ActionPermission $actionPermission
     * @param PlanRepositoryInterface $planRepository
     * @param TitleResolver $planTitleResolver
     * @param TokenActiveChecker $profileTokenChecker
     * @param array $data
     */
    public function __construct(
        Context $context,
        ProfileManagementInterface $profileManagement,
        StatusSource $statusSource,
        ProductRepositoryInterface $productRepository,
        ProductUrl $productUrl,
        CurrencyFactory $currencyFactory,
        ActionPermission $actionPermission,
        PlanRepositoryInterface $planRepository,
        TitleResolver $planTitleResolver,
        TokenActiveChecker $profileTokenChecker,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->profileManagement = $profileManagement;
        $this->statusSource = $statusSource;
        $this->productRepository = $productRepository;
        $this->productUrl = $productUrl;
        $this->currencyFactory = $currencyFactory;
        $this->actionPermission = $actionPermission;
        $this->planRepository = $planRepository;
        $this->planTitleResolver = $planTitleResolver;
        $this->profileTokenChecker = $profileTokenChecker;
    }

    /**
     * Get status label
     *
     * @param string $status
     * @return string
     */
    public function getStatusLabel($status)
    {
        $statusOptions = $this->statusSource->getOptions();
        return $statusOptions[$status];
    }

    /**
     * Check if product exists
     *
     * @param int $productId
     * @return bool
     */
    public function isProductExists($productId)
    {
        try {
            $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return false;
        }
        return true;
    }

    /**
     * Check if product has url
     *
     * @param int $productId
     * @return bool
     */
    public function hasProductUrl($productId)
    {
        /** @var ProductInterface|Product $product */
        $product = $this->productRepository->getById($productId);
        if ($product->getVisibleInSiteVisibilities()) {
            return true;
        }
        if ($product->hasUrlDataObject()) {
            if (in_array($product->hasUrlDataObject()->getVisibility(), $product->getVisibleInSiteVisibilities())) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get product url
     *
     * @param int $productId
     * @return string
     */
    public function getProductUrl($productId)
    {
        /** @var ProductInterface|Product $product */
        $product = $this->productRepository->getById($productId);
        return $this->productUrl->getUrl($product);
    }

    /**
     * Check if cancel action available
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isCancelActionAvailable($profileId)
    {
        return $this->actionPermission->isCancelActionAvailableForCustomer($profileId)
               && $this->actionPermission->isCancelActionAvailableOnPeriodHolder($profileId);
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
        return $this->actionPermission->isExtendActionAvailable($profileId);
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
        return $this->actionPermission->isEditActionAvailable($profileId);
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
        return $this->actionPermission->isRenewActionAvailable($profileId);
    }

    /**
     * Check if edit plan action available
     *
     * @param int $profileId
     * @return bool
     * @throws LocalizedException
     */
    public function isEditPlanActionAvailable($profileId)
    {
        return $this->actionPermission->isEditPlanActionAvailable($profileId);
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
        return $this->actionPermission->isEditNextPaymentDateActionAvailable($profileId);
    }

    /**
     * Check if edit address action available
     *
     * @param ProfileInterface $profile
     * @return bool
     * @throws LocalizedException
     */
    public function isEditAddressActionAvailable($profile)
    {
        return $this->actionPermission->isEditAddressActionAvailable($profile->getProfileId());
    }

    /**
     * Check if edit address action available
     *
     * @param ProfileInterface $profile
     * @return bool
     * @throws LocalizedException
     */
    public function isEditPaymentMethodActionAvailable($profile)
    {
        return $this->actionPermission->isEditActionAvailable($profile->getProfileId());
    }

    /**
     * Get cancel profile url
     *
     * @param int $profileId
     * @return string
     */
    public function getCancelUrl($profileId)
    {
        return $this->_urlBuilder->getUrl(
            'aw_sarp2/profile/cancel',
            ['profile_id' => $profileId]
        );
    }

    /**
     * Get extend profile url
     *
     * @param int $profileId
     * @return string
     */
    public function getExtendUrl($profileId)
    {
        return $this->_urlBuilder->getUrl(
            'aw_sarp2/profile/extend',
            ['profile_id' => $profileId]
        );
    }

    /**
     * Get edit product item url
     *
     * @param int $profileId
     * @param int $itemId
     * @return string
     */
    public function getEditItemUrl($profileId, $itemId)
    {
        return $this->_urlBuilder->getUrl(
            'aw_sarp2/profile_edit/item',
            [
                'profile_id' => $profileId,
                'item_id' => $itemId
            ]
        );
    }

    /**
     * Get remove product item url
     *
     * @param int $profileId
     * @param int $itemId
     * @return string
     */
    public function getRemoveItemUrl($profileId, $itemId)
    {
        return $this->_urlBuilder->getUrl(
            'aw_sarp2/profile_edit/removeItem',
            [
                'profile_id' => $profileId,
                'item_id' => $itemId
            ]
        );
    }

    /**
     * Get last payment date info
     *
     * @param ProfileInterface $profile
     * @return string
     */
    public function getLastPaymentDateInfo($profile)
    {
        return $profile->getLastOrderDate()
            ? $this->formatDate($profile->getLastOrderDate(), \IntlDateFormatter::MEDIUM)
            : '';
    }

    /**
     * Check if need show warning tooltip
     *
     * @param ProfileInterface $profile
     * @return bool
     * @throws LocalizedException
     */
    public function isShowInvalidTokenTooltip($profile)
    {
        $notHighlightStatuses = [
            StatusSource::CANCELLED,
            StatusSource::EXPIRED,
        ];

        $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profile->getProfileId());
        $isMembership =
            $nextPaymentInfo->getPaymentStatus() == ScheduledPaymentInfoInterface::PAYMENT_STATUS_LAST_PERIOD_HOLDER;

        return !$this->profileTokenChecker->check($profile)
            && !in_array($profile->getStatus(), $notHighlightStatuses)
            && !$isMembership;
    }

    /**
     * Get next payment date info
     *
     * @param ProfileInterface $profile
     * @return string
     */
    public function getNextPaymentDateInfo($profile)
    {
        if ($this->canShowNextPaymentInfo($profile)) {
            $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profile->getProfileId());
            $nextPaymentDate = $nextPaymentInfo->getPaymentDate();
            if ($nextPaymentDate) {
                $nextPaymentDateFormatted = $this->formatDate($nextPaymentDate, \IntlDateFormatter::MEDIUM);
                $paymentStatus = $nextPaymentInfo->getPaymentStatus();
                return $paymentStatus == ScheduledPaymentInfoInterface::PAYMENT_STATUS_REATTEMPT
                    ? __('Scheduled payment was failed. The next charge attempt: %1', $nextPaymentDateFormatted)
                    : $nextPaymentDateFormatted;
            }
            return $nextPaymentDate
                ? $this->formatDate($nextPaymentDate, \IntlDateFormatter::MEDIUM)
                : '';
        } else {
            return $profile->getStatus() == StatusSource::ACTIVE
                ? __('All payments are done')
                : ($profile->getStatus() == StatusSource::EXPIRED ? __('Finished') : __('Cancelled'));
        }
    }

    /**
     * Get next payment amount html
     *
     * @param ProfileInterface $profile
     * @return string
     */
    public function getNextPaymentAmountHtml($profile)
    {
        if ($this->canShowNextPaymentInfo($profile)) {
            $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profile->getProfileId());
            /** @var Currency $profileCurrency */
            $profileCurrency = $this->currencyFactory->create();
            $profileCurrency->load($profile->getProfileCurrencyCode());
            return $profileCurrency->formatPrecision($nextPaymentInfo->getAmount(), 2);
        }
        return '';
    }

    /**
     * Get next plan name
     *
     * @param ProfileInterface $profile
     * @return string
     */
    public function getPlanName($profile)
    {
        try {
            $plan = $this->planRepository->get($profile->getPlanId());
            $planName = $this->planTitleResolver->getTitle($plan);
        } catch (LocalizedException $e) {
            $planName = $profile->getPlanName();
        }

        return $planName;
    }

    /**
     * Check if next payment info can be shown
     *
     * @param ProfileInterface $profile
     * @return bool
     */
    private function canShowNextPaymentInfo($profile)
    {
        $result = !in_array(
            $profile->getStatus(),
            [StatusSource::CANCELLED, StatusSource::EXPIRED]
        );
        if ($result) {
            $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profile->getProfileId());
            $result = !($nextPaymentInfo->getPaymentStatus()
                == ScheduledPaymentInfoInterface::PAYMENT_STATUS_LAST_PERIOD_HOLDER);
        }

        return $result;
    }
}
