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
namespace Aheadworks\Sarp2\Test\Integration\Engine;

use Aheadworks\Sarp2\Engine\Config;

/**
 * Class ConfigStub
 * @package Aheadworks\Sarp2\Test\Integration\Engine
 */
class ConfigStub extends Config
{
    /**
     * @var bool|null
     */
    private $isBundlePaymentsEnabled = null;

    /**
     * @var bool|null
     */
    private $isVirtualBundleEnabled = null;

    /**
     * @var string|null
     */
    private $bundledFailureHandlingType = null;

    /**
     * Set bundled payments enabled flag
     *
     * @param bool $flag
     * @return void
     */
    public function setIsBundledPaymentsEnabled($flag)
    {
        $this->isBundlePaymentsEnabled = $flag;
    }

    /**
     * {@inheritdoc}
     */
    public function isBundledPaymentsEnabled()
    {
        return $this->isBundlePaymentsEnabled !== null
            ? $this->isBundlePaymentsEnabled
            : parent::isBundledPaymentsEnabled();
    }

    /**
     * Set virtual profile bundle enabled flag
     *
     * @param bool $flag
     * @return void
     */
    public function setIsVirtualBundleEnabled($flag)
    {
        $this->isVirtualBundleEnabled = $flag;
    }

    /**
     * {@inheritdoc}
     */
    public function isVirtualProfilesBundleEnabled($storeId = null)
    {
        return $this->isVirtualBundleEnabled !== null
            ? $this->isVirtualBundleEnabled
            : parent::isVirtualProfilesBundleEnabled($storeId);
    }

    /**
     * Set bundled payments failure handling type
     *
     * @param string $handlingType
     * @return void
     */
    public function setBundledFailureHandlingType($handlingType)
    {
        $this->bundledFailureHandlingType = $handlingType;
    }

    /**
     * {@inheritdoc}
     */
    public function getBundledFailureHandlingType($storeId = null)
    {
        return $this->bundledFailureHandlingType !== null
            ? $this->bundledFailureHandlingType
            : parent::getBundledFailureHandlingType($storeId);
    }
}
