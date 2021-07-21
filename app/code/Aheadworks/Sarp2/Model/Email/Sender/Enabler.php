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
namespace Aheadworks\Sarp2\Model\Email\Sender;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Enabler
 * @package Aheadworks\Sarp2\Model\Email\Sender
 */
class Enabler implements EnablerInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var string
     */
    private $configPath;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param string $configPath
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        $configPath
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configPath = $configPath;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled($notification)
    {
        $storeId = $notification->getStoreId();
        return $this->scopeConfig->isSetFlag(
            $this->configPath,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
