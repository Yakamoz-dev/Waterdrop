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
 * @package    Sarp2Stripe
 * @version    1.0.5
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Model\Profile\View\Edit\Payment\ConfigProvider;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

/**
 * Class CheckoutUrlProvider
 *
 * @package Aheadworks\Sarp2Stripe\Model\Profile\View\Edit\Payment\ConfigProvider
 */
class CheckoutUrlProvider implements ConfigProviderInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Registry $registry,
        UrlInterface $urlBuilder
    ) {
        $this->registry = $registry;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $config['stripeSavePaymentUrl'] = $this->getSavePaymentUrl();
        $config['defaultSuccessPageUrl'] = $this->getSuccessPageUrl();

        return $config;
    }

    /**
     * Get profile
     *
     * @return ProfileInterface
     */
    private function getProfile()
    {
        return $this->registry->registry('profile');
    }

    /**
     * Retrieve save payment url
     *
     * @return string
     */
    public function getSavePaymentUrl()
    {
        return $this->urlBuilder->getUrl(
            'aw_sarp2_stripe/profile_edit/savePayment',
            ['profile_id' => $this->getProfile()->getProfileId()]
        );
    }

    /**
     * Retrieve save payment url
     *
     * @return string
     */
    public function getSuccessPageUrl()
    {
        return $this->urlBuilder->getUrl(
            'aw_sarp2/profile_edit/index',
            ['profile_id' => $this->getProfile()->getProfileId()]
        );
    }
}
