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
namespace Aheadworks\Sarp2\Engine\Payment\Failure\Handler;

use Aheadworks\Sarp2\Engine\Config;
use Aheadworks\Sarp2\Engine\Payment\Failure\HandlerInterface;
use Aheadworks\Sarp2\Engine\Payment\Failure\Handler\Bundled\RetryBundled;
use Aheadworks\Sarp2\Engine\Payment\Failure\Handler\Single\DefaultHandler;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class Factory
 * @package Aheadworks\Sarp2\Engine\Payment\Failure\Handler
 */
class Factory
{
    /**
     * Bundled handling types
     */
    const BUNDLED_HANDLING_TYPE_RETRY_BUNDLED = 'retry_bundled';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Config
     */
    private $engineConfig;

    /**
     * @var array
     */
    private $bundledHandlers = [
        self::BUNDLED_HANDLING_TYPE_RETRY_BUNDLED => RetryBundled::class
    ];

    /**
     * @param ObjectManagerInterface $objectManager
     * @param Config $engineConfig
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Config $engineConfig
    ) {
        $this->objectManager = $objectManager;
        $this->engineConfig = $engineConfig;
    }

    /**
     * Create payment failure instance
     *
     * @param string $type
     * @return HandlerInterface
     */
    public function create($type)
    {
        if ($type == HandlerInterface::TYPE_SINGLE) {
            $className = DefaultHandler::class;
        } else {
            $bundleHandlingType = $this->engineConfig->getBundledFailureHandlingType();
            if (!isset($this->bundledHandlers[$bundleHandlingType])) {
                throw new \LogicException('Unknown bundle failures handling type: ' . $bundleHandlingType);
            }
            $className = $this->bundledHandlers[$bundleHandlingType];
        }

        $instance = $this->objectManager->create($className);
        if (!$instance instanceof HandlerInterface) {
            throw new \InvalidArgumentException(
                $className . ' doesn\'t implement ' . HandlerInterface::class
            );
        }
        return $instance;
    }
}
