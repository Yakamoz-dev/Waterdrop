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
namespace Aheadworks\Sarp2\Model\Integration;

use Aheadworks\Sarp2\Model\Integration\ModuleAvailability\CheckerInterface;
use Aheadworks\Sarp2\Model\Payment\Method\Data\DataAssignerInterface;
use Aheadworks\Sarp2\Model\Profile\View\Edit\Payment\ConfigProcessor\ConfigProcessorInterface;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class IntegratedMethod
 *
 * @package Aheadworks\Sarp2\Model\Integration
 */
class IntegratedMethod implements IntegratedMethodInterface
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $recurringCode;

    /**
     * @var string
     */
    private $moduleName;

    /**
     * @var bool
     */
    private $needCopyMethodRendererFromCheckoutLayout;

    /**
     * @var string|null
     */
    private $checkoutPaymentMethodRendererComponentName;

    /**
     * @var ConfigProviderInterface
     */
    private $configProvider;

    /**
     * @var ConfigProcessorInterface
     */
    private $configProcessor;

    /**
     * @var CheckerInterface
     */
    private $moduleAvailabilityChecker;

    /**
     * @var DataAssignerInterface
     */
    private $paymentDataAssigner;

    /**
     * @param string $code
     * @param string $recurringCode
     * @param string $paymentModuleName
     * @param CheckerInterface $moduleAvailabilityChecker
     * @param DataAssignerInterface $paymentDataAssigner
     * @param ConfigProviderInterface $configProvider
     * @param ConfigProcessorInterface|null $configProcessor
     * @param bool $needCopyMethodRendererFromCheckoutLayout
     * @param null $checkoutPaymentMethodRendererComponentName
     */
    public function __construct(
        string $code,
        string $recurringCode,
        string $paymentModuleName,
        CheckerInterface $moduleAvailabilityChecker,
        DataAssignerInterface $paymentDataAssigner,
        ConfigProviderInterface $configProvider,
        ConfigProcessorInterface $configProcessor = null,
        bool $needCopyMethodRendererFromCheckoutLayout = true,
        $checkoutPaymentMethodRendererComponentName = null
    ) {
        $this->code = $code;
        $this->recurringCode = $recurringCode;
        $this->moduleName = $paymentModuleName;
        $this->configProvider = $configProvider;
        $this->configProcessor = $configProcessor;
        $this->moduleAvailabilityChecker = $moduleAvailabilityChecker;
        $this->paymentDataAssigner = $paymentDataAssigner;
        $this->needCopyMethodRendererFromCheckoutLayout = $needCopyMethodRendererFromCheckoutLayout;
        $this->checkoutPaymentMethodRendererComponentName = $checkoutPaymentMethodRendererComponentName;
    }

    /**
     * @inheritDoc
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @inheritDoc
     */
    public function getRecurringCode()
    {
        return $this->recurringCode;
    }

    /**
     * @inheritDoc
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @inheritDoc
     */
    public function needCopyMethodRendererFromCheckoutLayout(): bool
    {
        return $this->needCopyMethodRendererFromCheckoutLayout;
    }

    /**
     * @inheritDoc
     */
    public function getCheckoutPaymentMethodRendererComponentName()
    {
        return null == $this->checkoutPaymentMethodRendererComponentName
            ? $this->getCode()
            : $this->checkoutPaymentMethodRendererComponentName;
    }

    /**
     * @inheritDoc
     */
    public function getProcessedConfig()
    {
        $config = $this->configProvider->getConfig();
        if (null !== $this->configProcessor) {
            $config = $this->configProcessor->process($config);
        }
        return $config;
    }

    /**
     * @inheritDoc
     */
    public function getConfigProvider()
    {
        return $this->configProvider;
    }

    /**
     * @inheritDoc
     */
    public function getConfigProcessor()
    {
        return $this->configProvider;
    }

    /**
     * @inheritDoc
     */
    public function isEnablePaymentModule(): bool
    {
        return $this->moduleAvailabilityChecker->check($this);
    }

    /**
     * @inheritDoc
     */
    public function getPaymentDataAssigner()
    {
        return $this->paymentDataAssigner;
    }
}
