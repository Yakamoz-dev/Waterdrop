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
namespace Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments;

use Aheadworks\Sarp2\Model\Integration\IntegratedMethodList;
use Magento\Framework\ObjectManagerInterface;
use Aheadworks\Sarp2\Model\ThirdPartyModule\Manager;

/**
 * Class CustomerManagerFactory
 *
 * @package Aheadworks\Sarp2Stripe\Model\Adapter\StripePayments
 */
class CustomerManagerFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Manager
     */
    private $thirdPartyModuleManager;

    /**
     * @var mixed|null
     */
    private $customerManager;

    /**
     * @var IntegratedMethodList
     */
    private $integratedMethodList;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param Manager $thirdPartyModuleManager
     * @param IntegratedMethodList $integratedMethodList
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Manager $thirdPartyModuleManager,
        IntegratedMethodList $integratedMethodList
    ) {
        $this->objectManager = $objectManager;
        $this->thirdPartyModuleManager = $thirdPartyModuleManager;
        $this->integratedMethodList = $integratedMethodList;
    }

    /**
     * Retrieve customer manager object if corresponding module is enabled
     *
     * @return \StripeIntegration\Payments\Model\StripeCustomer|null
     */
    public function getCustomerManager()
    {
        if (empty($this->customerManager)) {
            try {
                $integratedMethod = $this->integratedMethodList->getMethod('stripe_payments');
                if ($integratedMethod->isEnablePaymentModule()) {
                    $this->customerManager = $this->objectManager->get(
                        \StripeIntegration\Payments\Model\StripeCustomer::class
                    );
                }
            } catch (\Exception $exception) {
                return $this->customerManager;
            }
        }

        return $this->customerManager;
    }
}
