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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Details\Config;

use Aheadworks\Sarp2\Model\Product\Subscription\Details\Config\Provider\Generic;

/**
 * Class ProviderPool
 *
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Details\Config
 */
class ProviderPool
{
    /**
     * @var ProviderInterface[]
     */
    private $providerInstances = [];

    /**
     * @var array
     */
    private $providers = [];

    /**
     * @var Generic
     */
    private $defaultProvider;

    /**
     * @var ProviderFactory
     */
    private $providerFactory;

    /**
     * @param ProviderFactory $providerFactory
     * @param Generic $defaultProvider
     * @param array $providers
     */
    public function __construct(
        ProviderFactory $providerFactory,
        Generic $defaultProvider,
        array $providers = []
    ) {
        $this->providerFactory = $providerFactory;
        $this->defaultProvider = $defaultProvider;
        $this->providers = array_merge($this->providers, $providers);
    }

    /**
     * Get regular price config provider instance
     *
     * @param string $typeId
     * @return ProviderInterface
     * @throws \Exception
     */
    public function getConfigProvider($typeId)
    {
        if (!isset($this->providerInstances[$typeId])) {
            $this->providerInstances[$typeId] = isset($this->providers[$typeId])
                ? $this->providerFactory->create($this->providers[$typeId])
                : $this->defaultProvider;
        }
        return $this->providerInstances[$typeId];
    }
}
