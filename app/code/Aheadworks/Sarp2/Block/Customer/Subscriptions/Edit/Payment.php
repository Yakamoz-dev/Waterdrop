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

use Aheadworks\Sarp2\Model\Profile\View\Edit\Payment\ConfigProvider\Composite as CompositeConfigProvider;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Payment
 *
 * @package Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit
 */
class Payment extends Template
{
    /**
     * @var JsonSerializer
     */
    private $serializer;

    /**
     * @var CompositeConfigProvider
     */
    private $configProvider;

    /**
     * @var LayoutProcessorInterface[]
     */
    protected $layoutProcessors;

    /**
     * @var LayoutProcessorInterface[]
     */
    protected $configProcessors;

    /**
     * @param Context $context
     * @param JsonSerializer $serializer
     * @param CompositeConfigProvider $configProvider
     * @param LayoutProcessorInterface[] $layoutProcessors
     * @param LayoutProcessorInterface[] $configProcessors
     * @param array $data
     */
    public function __construct(
        Context $context,
        JsonSerializer $serializer,
        CompositeConfigProvider $configProvider,
        array $layoutProcessors = [],
        array $configProcessors = [],
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->serializer = $serializer;
        $this->configProvider = $configProvider;
        $this->layoutProcessors = $layoutProcessors;
        $this->configProcessors = $configProcessors;
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout'])
            ? $data['jsLayout']
            : [];
    }

    /**
     * @inheritdoc
     */
    public function getJsLayout()
    {
        foreach ($this->layoutProcessors as $processor) {
            $this->jsLayout = $processor->process($this->jsLayout);
        }

        return $this->serializer->serialize($this->jsLayout);
    }

    /**
     * Retrieve serialized checkout config.
     *
     * @return bool|string
     */
    public function getSerializedCheckoutConfig()
    {
        $checkoutConfig = $this->configProvider->getConfig();

        foreach ($this->configProcessors as $processor) {
            $checkoutConfig = $processor->process($checkoutConfig);
        }

        return  $this->serializer->serialize($checkoutConfig);
    }
}
