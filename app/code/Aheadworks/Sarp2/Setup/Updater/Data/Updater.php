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
namespace Aheadworks\Sarp2\Setup\Updater\Data;

use Aheadworks\Sarp2\Model\Product\Attribute\AttributeName;
use Aheadworks\Sarp2\Model\Product\Type\Plugin\Config;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class Updater
 * @package Aheadworks\Sarp2\Setup\Updater\Data
 */
class Updater
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var ConfigInterface
     */
    private $productTypeConfig;

    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param ConfigInterface $productTypeConfig
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ConfigInterface $productTypeConfig
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productTypeConfig = $productTypeConfig;
    }

    /**
     * Update to 220
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    public function updateTo220(ModuleDataSetupInterface $setup)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->updateAttribute(
            Product::ENTITY,
            'aw_sarp2_subscription_options',
            'frontend_input_renderer',
            \Aheadworks\Sarp2\Block\Adminhtml\Product\SubscriptionOptions::class
        );

        return $this;
    }

    /**
     * Update to 270
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    public function updateTo270(ModuleDataSetupInterface $setup)
    {
        $this->updateTrialPeriodFields($setup);
        $this->updateMembershipFields($setup);

        return $this;
    }

    /**
     * Update to 2.8.0
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    public function updateTo280(ModuleDataSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $connection->query(
            'UPDATE ' . $setup->getTable('core_config_data') .
            " SET
                    `path` = REPLACE(`path`, 'general', 'product_page')" .
            " WHERE `path` LIKE 'aw_sarp2/general/subscribe_and_save%'"
        );

        return $this;
    }

    /**
     * Update to 2.12.0
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function updateTo2_12(ModuleDataSetupInterface $setup)
    {
        $this->createAdvancedPricingEavAttribute($setup);
        $this->moveAlternativeViewConfigSettings($setup);

        return $this;
    }

    /**
     * Update to 2.14.0
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function updateTo2_14(ModuleDataSetupInterface $setup)
    {
        $this->updateProductEacAttributes($setup);

        return $this;
    }

    /**
     * Update separate trial period fields
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    private function updateTrialPeriodFields(ModuleDataSetupInterface $setup)
    {
        $connection = $setup->getConnection();

        $tables = ['aw_sarp2_plan_definition', 'aw_sarp2_profile_definition'];
        foreach ($tables as $table) {
            $connection->query(
                'UPDATE ' . $setup->getTable($table) .
                ' SET
                    trial_billing_period = billing_period,
                    trial_billing_frequency = billing_frequency,
                    upcoming_trial_billing_email_offset = upcoming_billing_email_offset' .
                ' WHERE is_trial_period_enabled = 1'
            );
        }

        $connection->query(
            'UPDATE ' . $setup->getTable('aw_sarp2_core_schedule') .
            ' SET
                    trial_period = period,
                    trial_frequency = frequency'
        );

        return $this;
    }

    /**
     * Update membership fields
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    private function updateMembershipFields(ModuleDataSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $table = $setup->getTable('aw_sarp2_core_schedule');

        // for expired subscriptions
        $connection->query(
            'UPDATE ' . $table .
            ' SET
                    regular_total_count = regular_total_count - 1,
                    regular_count = regular_count - 1,
                    membership_total_count = 1,
                    membership_count = 1' .
            ' WHERE is_membership_model = 1
                    AND regular_total_count > 0
                    AND regular_total_count = regular_count'
        );

        // for Not expired subscriptions
        $connection->query(
            'UPDATE ' . $table .
            ' SET
                    regular_total_count = regular_total_count - 1,
                    membership_total_count = 1' .
            ' WHERE is_membership_model = 1
                    AND regular_total_count > 0
                    AND regular_total_count <> regular_count'
        );

        return $this;
    }

    /**
     * Create Advanced Pricing attribute
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    protected function createAdvancedPricingEavAttribute(ModuleDataSetupInterface $setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->updateAttribute(
            Product::ENTITY,
            AttributeName::AW_SARP2_SUBSCRIPTION_TYPE,
            'sort_order',
            10,
            10
        );
        $eavSetup->updateAttribute(
            Product::ENTITY,
            AttributeName::AW_SARP2_SUBSCRIPTION_OPTIONS,
            'sort_order',
            30,
            30
        );

        $applyTo = implode(
            ',',
            $this->productTypeConfig->filter(Config::SUPPORTED_CUSTOM_ATTR_CODE, true)
        );
        $eavSetup->addAttribute(
            Product::ENTITY,
            AttributeName::AW_SARP2_IS_USED_ADVANCED_PRICING,
            [
                'type' => 'int',
                'group' => 'Sarp2: Subscription Configuration',
                'label' => 'Use Product Advanced Pricing',
                'input' => 'select',
                'sort_order' => 20,
                'backend' => \Aheadworks\Sarp2\Model\Product\Attribute\Backend\BooleanWithConfig::class,
                'source' => \Magento\Catalog\Model\Product\Attribute\Source\Boolean::class,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => \Magento\Catalog\Model\Product\Attribute\Source\Boolean::VALUE_USE_CONFIG,
                'apply_to' => $applyTo,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
            ]
        );

        return $this;
    }

    /**
     * Move alternative view config parameter
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     */
    protected function moveAlternativeViewConfigSettings(ModuleDataSetupInterface $setup)
    {
        $connection = $setup->getConnection();
        $connection->query(
            'UPDATE ' . 'IGNORE ' . $setup->getTable('core_config_data') .
            " SET
                    `path` = REPLACE(`path`, 'general', 'product_page')" .
            " WHERE `path` LIKE 'aw_sarp2/general/alternative_subscription_period_details_view'"
        );

        return $this;
    }

    /**
     * Create Advanced Pricing attribute
     *
     * @param ModuleDataSetupInterface $setup
     * @return $this
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    protected function updateProductEacAttributes(ModuleDataSetupInterface $setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $applyTo = implode(
            ',',
            $this->productTypeConfig->filter(Config::SUPPORTED_CUSTOM_ATTR_CODE, true)
        );

        $eavSetup->updateAttribute(
            Product::ENTITY,
            AttributeName::AW_SARP2_SUBSCRIPTION_TYPE,
            'apply_to',
            $applyTo
        );
        $eavSetup->updateAttribute(
            Product::ENTITY,
            AttributeName::AW_SARP2_SUBSCRIPTION_OPTIONS,
            'apply_to',
            $applyTo
        );
        $eavSetup->updateAttribute(
            Product::ENTITY,
            AttributeName::AW_SARP2_IS_USED_ADVANCED_PRICING,
            'apply_to',
            $applyTo
        );

        return $this;
    }
}
