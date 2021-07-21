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
namespace Aheadworks\Sarp2\Setup;

use Aheadworks\Sarp2\Model\Product\Attribute\Backend\SubscriptionOptions\Proxy as SubscriptionOptionsProxy;
use Aheadworks\Sarp2\Setup\Updater\Data\Updater;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class UpgradeData
 * @package Aheadworks\Sarp2\Setup
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var Updater
     */
    private $updater;

    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param Updater $updater
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        Updater $updater
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->updater = $updater;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.4', '<')) {
            $this->updateSubscriptionOptionsProductAttribute($setup);
        }
        if (version_compare($context->getVersion(), '2.2.0', '<')) {
            $this->updater->updateTo220($setup);
        }
        if (version_compare($context->getVersion(), '2.7.0', '<')) {
            $this->updater->updateTo270($setup);
        }
        if (version_compare($context->getVersion(), '2.8.0', '<')) {
            $this->updater->updateTo280($setup);
        }
        if (version_compare($context->getVersion(), '2.12.0', '<')) {
            $this->updater->updateTo2_12($setup);
        }
        if (version_compare($context->getVersion(), '2.14.0', '<')) {
            $this->updater->updateTo2_14($setup);
        }
    }

    /**
     * Update 'aw_sarp2_subscription_options' product attribute
     *
     * @param ModuleDataSetupInterface $setup
     * @return void
     */
    private function updateSubscriptionOptionsProductAttribute(ModuleDataSetupInterface $setup)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->updateAttribute(
            Product::ENTITY,
            'aw_sarp2_subscription_options',
            'backend_model',
            SubscriptionOptionsProxy::class
        );
    }
}
