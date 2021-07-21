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
namespace Aheadworks\Sarp2\Model\Profile\View\Edit\Payment\ConfigProvider;

use Magento\Checkout\Model\ConfigProviderInterface;
use Aheadworks\Sarp2\Api\PaymentInfoManagementInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Checkout\Api\Data\PaymentDetailsInterface;

/**
 * Class DefaultConfig
 *
 * @package Aheadworks\Sarp2\Model\Profile\View\Edit\Payment\ConfigProvider
 */
class PaymentMethods implements ConfigProviderInterface
{
    /**
     * @var PaymentInfoManagementInterface
     */
    private $paymentInfoManagement;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @param PaymentInfoManagementInterface $paymentInfoManagement
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        PaymentInfoManagementInterface $paymentInfoManagement,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->paymentInfoManagement = $paymentInfoManagement;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Return configuration array
     *
     * @return array
     */
    public function getConfig()
    {
        $paymentDetails = $this->paymentInfoManagement->getPaymentInfoForSubscription();
        $paymentDetailsAsArray = $this->dataObjectProcessor->buildOutputDataArray(
            $paymentDetails,
            PaymentDetailsInterface::class
        );
        $config['paymentMethods'] = $paymentDetailsAsArray['payment_methods'];

        return $config;
    }
}
