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
namespace Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit;

use Aheadworks\Sarp2\Api\Data\ProfileAddressInterface;
use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Aheadworks\Sarp2\Block\Customer\Subscription;
use Aheadworks\Sarp2\Engine\Profile\Checker\PaymentToken as TokenActiveChecker;
use Aheadworks\Sarp2\Model\Plan\Resolver\TitleResolver;
use Aheadworks\Sarp2\Model\Profile\Address\Renderer as AddressRenderer;
use Aheadworks\Sarp2\Model\Profile\Item\Checker\IsOneOffItem;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Sarp2\Model\Profile\Source\Status as StatusSource;
use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Url as ProductUrl;
use Magento\Directory\Model\CurrencyFactory;
use Aheadworks\Sarp2\Model\Profile\View\Action\Permission as ActionPermission;
use Magento\Framework\View\Element\Template;

/**
 * Class View
 * @package Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit
 */
class View extends Subscription
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var AddressRenderer
     */
    private $addressRenderer;

    /**
     * @var IsOneOffItem
     */
    private $isOneOffItem;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ProfileManagementInterface $profileManagement
     * @param StatusSource $statusSource
     * @param ProductRepositoryInterface $productRepository
     * @param ProductUrl $productUrl
     * @param CurrencyFactory $currencyFactory
     * @param ActionPermission $actionPermission
     * @param AddressRenderer $addressRenderer
     * @param PlanRepositoryInterface $planRepository
     * @param TitleResolver $titleResolver
     * @param TokenActiveChecker $profileTokenChecker
     * @param IsOneOffItem $isOneOffItem
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ProfileManagementInterface $profileManagement,
        StatusSource $statusSource,
        ProductRepositoryInterface $productRepository,
        ProductUrl $productUrl,
        CurrencyFactory $currencyFactory,
        ActionPermission $actionPermission,
        AddressRenderer $addressRenderer,
        PlanRepositoryInterface $planRepository,
        TitleResolver $titleResolver,
        TokenActiveChecker $profileTokenChecker,
        IsOneOffItem $isOneOffItem,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $profileManagement,
            $statusSource,
            $productRepository,
            $productUrl,
            $currencyFactory,
            $actionPermission,
            $planRepository,
            $titleResolver,
            $profileTokenChecker,
            $data
        );
        $this->registry = $registry;
        $this->addressRenderer = $addressRenderer;
        $this->isOneOffItem = $isOneOffItem;
    }

    /**
     * Retrieve profile
     *
     * @return ProfileInterface
     */
    public function getProfile()
    {
        return $this->registry->registry('profile');
    }

    /**
     * Get subscription plan edit url
     *
     * @param int $profileId
     * @return string
     */
    public function getSubscriptionPlanEditUrl($profileId)
    {
        return $this->_urlBuilder->getUrl(
            'aw_sarp2/profile_edit/plan',
            ['profile_id' => $profileId]
        );
    }

    /**
     * Get subscription plan edit url
     *
     * @param int $profileId
     * @return string
     */
    public function getNextPaymentDateEditUrl($profileId)
    {
        return $this->_urlBuilder->getUrl(
            'aw_sarp2/profile_edit/nextPaymentDate',
            ['profile_id' => $profileId]
        );
    }

    /**
     * Get shipping address edit url
     *
     * @param int $profileId
     * @return string
     */
    public function getShippingAddressEditUrl($profileId)
    {
        return $this->_urlBuilder->getUrl(
            'aw_sarp2/profile_edit/address',
            ['profile_id' => $profileId]
        );
    }

    /**
     * Get payment details edit url
     *
     * @param int $profileId
     * @return string
     */
    public function getPaymentDetailsEditUrl($profileId)
    {
        return $this->_urlBuilder->getUrl(
            'aw_sarp2/profile_edit/payment',
            ['profile_id' => $profileId]
        );
    }

    /**
     * Retrieve string with formatted address
     *
     * @param ProfileAddressInterface $address
     * @return null|string
     */
    public function getFormattedAddress($address)
    {
        return $this->addressRenderer->render($address);
    }

    /**
     * Retrieve payment details html
     *
     * @param ProfileInterface $profile
     * @return string
     */
    public function getPaymentDetailsHtml($profile)
    {
        $paymentDetailsHtml = '';
        /** @var Template $paymentDetailsTemplate */
        $paymentDetailsTemplate = $this->getChildBlock(
            'aw_sarp2.customer.subscriptions.edit.view.payment.details'
        );
        if ($paymentDetailsTemplate && $paymentDetailsTemplate instanceof Template) {
            $paymentDetailsTemplate->assign('profile', $profile);
            $paymentDetailsHtml = $paymentDetailsTemplate->toHtml();
        }
        return $paymentDetailsHtml;
    }

    /**
     * Retrieve item name
     *
     * @param ProfileItemInterface $item
     * @return string
     */
    public function getItemName($item)
    {
        $name = $item->getName();
        if ($item->getProductType() == Configurable::TYPE_CODE) {
            $name .= ' (' . $item->getSku() . ')';
        }

        return $name;
    }

    /**
     * Check if edit item action available
     *
     * @param ProfileInterface $profile
     * @return bool
     * @throws LocalizedException
     */
    public function isEditItemActionAvailable($profile)
    {
        return $this->actionPermission->isEditProductItemActionAvailable($profile->getProfileId());
    }

    /**
     * Check if remove action available
     *
     * @param ProfileInterface $profile
     * @param ProfileItemInterface $profileItem
     * @return bool
     * @throws LocalizedException
     */
    public function isRemoveActionAvailable(ProfileInterface $profile, ProfileItemInterface $profileItem): bool
    {
        $itemsCount = 0;
        $subscriptionItems = 0;
        $oneOffItems = 0;

        foreach ($profile->getItems() as $item) {
            if (!$item->getParentItem()) {
                $itemsCount++;
            }
            $this->isOneOffItem->check($item) ? $oneOffItems++ : $subscriptionItems++;
        }

        return $this->isOneOffItem->check($profileItem)
            || $itemsCount - $oneOffItems > 1
            && $this->actionPermission->isCancelStatusAvailable($profile->getProfileId());
    }
}
