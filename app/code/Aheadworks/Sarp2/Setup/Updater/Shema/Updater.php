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
namespace Aheadworks\Sarp2\Setup\Updater\Shema;

use Aheadworks\Sarp2\Api\Data\AccessTokenInterface;
use Aheadworks\Sarp2\Api\Data\PlanDefinitionInterface;
use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Engine\Payment\ScheduleInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class Updater
 * @package Aheadworks\Sarp2\Setup\Updater\Shema
 */
class Updater
{
    /**
     * Upgrade to version 2.2.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo220(SchemaSetupInterface $installer)
    {
        $this
            ->addMembershipFields($installer)
            ->addUpcomingBillingEmailOffsetField($installer)
            ->addProfileDefinition($installer)
            ->copyDefinitionFromPlanToProfile($installer);

        return $this;
    }

    /**
     * Install version 2.2.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install220(SchemaSetupInterface $installer)
    {
        $this
            ->addMembershipFields($installer)
            ->addUpcomingBillingEmailOffsetField($installer)
            ->addProfileDefinition($installer);

        return $this;
    }

    /**
     * Upgrade to version 2.3.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo230(SchemaSetupInterface $installer)
    {
        $this
            ->addPaymentTokenDetails($installer)
            ->createPaymentSamplerTable($installer)
        ;

        return $this;
    }

    /**
     * Install version 2.3.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install230(SchemaSetupInterface $installer)
    {
        $this
            ->addPaymentTokenDetails($installer)
            ->createPaymentSamplerTable($installer)
        ;

        return $this;
    }

    /**
     * Upgrade to version 2.4.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo240(SchemaSetupInterface $installer)
    {
        $this->addSortOrder($installer);

        return $this;
    }

    /**
     * Install version 2.4.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install240(SchemaSetupInterface $installer)
    {
        $this->addSortOrder($installer);

        return $this;
    }

    /**
     * Upgrade to version 2.5.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo250(SchemaSetupInterface $installer)
    {
        $this->changePaymentTokenColumn($installer);

        return $this;
    }

    /**
     * Install version 2.5.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install250(SchemaSetupInterface $installer)
    {
        $this->changePaymentTokenColumn($installer);

        return $this;
    }

    /**
     * Upgrade to version 2.6.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo260(SchemaSetupInterface $installer)
    {
        $this->addInstallmentsModeField($installer);

        return $this;
    }

    /**
     * Install version 2.6.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install260(SchemaSetupInterface $installer)
    {
        $this->addInstallmentsModeField($installer);

        return $this;
    }

    /**
     * Upgrade to version 2.7.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo270(SchemaSetupInterface $installer)
    {
        $this->addTrialPeriodProfileDifferentFields($installer);
        $this->addTrialPeriodScheduleDifferentFields($installer);
        $this->addOfferExtendOptionFields($installer);
        $this->createAccessTokenTable($installer);
        $this->addMembershipModelCountFields($installer);

        return $this;
    }

    /**
     * Install version 2.7.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install270(SchemaSetupInterface $installer)
    {
        $this->addTrialPeriodProfileDifferentFields($installer);
        $this->addTrialPeriodScheduleDifferentFields($installer);
        $this->addOfferExtendOptionFields($installer);
        $this->createAccessTokenTable($installer);
        $this->addMembershipModelCountFields($installer);

        return $this;
    }

    /**
     * Upgrade to version 2.9.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo290(SchemaSetupInterface $installer)
    {
        $this->addReplacementItemIdField($installer);

        return $this;
    }

    /**
     * Install version 2.9.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install290(SchemaSetupInterface $installer)
    {
        $this->addReplacementItemIdField($installer);

        return $this;
    }

    /**
     * Upgrade to version 2.10.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo2100(SchemaSetupInterface $installer)
    {
        $this->addWasGuestField($installer);

        return $this;
    }

    /**
     * Install version 2.10.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install2100(SchemaSetupInterface $installer)
    {
        $this->addWasGuestField($installer);

        return $this;
    }

    /**
     * Upgrade to version 2.11.2
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo2_11_2(SchemaSetupInterface $installer)
    {
        $this->addMembershipActiveUntilField($installer);

        return $this;
    }

    /**
     * Install version 2.11.2
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install2_11_2(SchemaSetupInterface $installer)
    {
        $this->addMembershipActiveUntilField($installer);

        return $this;
    }

    /**
     * Upgrade to version 2.12
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo2_12(SchemaSetupInterface $installer)
    {
        $this->addPlanDefinitionAllowCancellationOptionField($installer);

        return $this;
    }

    /**
     * Install version 2.12
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install2_12(SchemaSetupInterface $installer)
    {
        $this->addPlanDefinitionAllowCancellationOptionField($installer);

        return $this;
    }

    /**
     * Upgrade to version 2.15.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function upgradeTo2_15_0(SchemaSetupInterface $installer)
    {
        $this->addPrimaryKeyToPlanTitleTable($installer);
        $this->addInitialFieldsToProfileTable($installer);
        $this->addInitialFeeFieldsToProfileItemTable($installer);

        return $this;
    }

    /**
     * Install version 2.15.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function install2_15_0(SchemaSetupInterface $installer)
    {
        $this->addPrimaryKeyToPlanTitleTable($installer);
        $this->addInitialFieldsToProfileTable($installer);
        $this->addInitialFeeFieldsToProfileItemTable($installer);

        return $this;
    }

    /**
     * Add columns list to tables
     *
     * @param SchemaSetupInterface $installer
     * @param array $tables
     * @param array $columns
     * @return $this
     */
    private function addColumnsToTables($installer, array $tables, array $columns)
    {
        foreach ($tables as $table) {
            foreach ($columns as $columnName => $columnDefinition) {
                $installer->getConnection()->addColumn(
                    $installer->getTable($table),
                    $columnName,
                    $columnDefinition
                );
            }
        }

        return $this;
    }

    /**
     * Add membership fields
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addMembershipFields($installer)
    {
        $connection = $installer->getConnection();

        $connection->addColumn(
            $installer->getTable('aw_sarp2_plan_definition'),
            'is_membership_model_enabled',
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => false,
                'comment' => 'Is Membership Model'
            ]
        );
        $connection->addColumn(
            $installer->getTable('aw_sarp2_core_schedule'),
            'is_membership_model',
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => false,
                'comment' => 'Is Membership Model'
            ]
        );

        return $this;
    }

    /**
     * Add upcoming billing email offset field
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addUpcomingBillingEmailOffsetField($installer)
    {
        $connection = $installer->getConnection();
        $connection->addColumn(
            $installer->getTable('aw_sarp2_plan_definition'),
            'upcoming_billing_email_offset',
            [
                'type' => Table::TYPE_SMALLINT,
                'nullable' => true,
                'unsigned' => true,
                'comment' => 'Upcoming billing email offset'
            ]
        );

        return $this;
    }

    /**
     * Add profile definition
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addProfileDefinition($installer)
    {
        $connection = $installer->getConnection();
        $connection->addColumn(
            $installer->getTable('aw_sarp2_profile'),
            'profile_definition_id',
            [
                'type' => Table::TYPE_INTEGER,
                'unsigned' => true,
                'nullable' => false,
                'default' => '0',
                'comment' => 'Profile Definition Id',
                'after'   => 'plan_definition_id',
            ]
        );

        /**
         * Create table 'aw_sarp2_profile_definition'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_sarp2_profile_definition'))
            ->addColumn(
                'definition_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Profile Definition Id'
            )->addColumn(
                'billing_period',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Billing Period'
            )->addColumn(
                'billing_frequency',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Billing Frequency'
            )->addColumn(
                'total_billing_cycles',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Total Billing Cycles'
            )->addColumn(
                'start_date_type',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Start Sate Type'
            )->addColumn(
                'start_date_day_of_month',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Day Of Month Of Start Date'
            )->addColumn(
                'is_initial_fee_enabled',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false],
                'Is Initial Fee Enabled'
            )->addColumn(
                'is_trial_period_enabled',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false],
                'Is Trial Period Enabled'
            )->addColumn(
                'trial_total_billing_cycles',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Trial Total Billing Cycles'
            )->addColumn(
                'is_membership_model_enabled',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'default' => false],
                'Is Membership Model'
            )->addColumn(
                'upcoming_billing_email_offset',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Upcoming billing email offset'
            )->setComment('Profile Definition');
        $installer->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Copy definition from plan to profile
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    public function copyDefinitionFromPlanToProfile($installer)
    {
        $connection = $installer->getConnection();
        $select = $connection->select()->from(
            $installer->getTable('aw_sarp2_profile'),
            ['plan_definition_id']
        )->group('plan_definition_id');
        $planDefinitionIds = $connection->fetchAssoc($select);

        $select = $connection->select()->from(
            $installer->getTable('aw_sarp2_plan_definition')
        )->where('definition_id IN (?)', $planDefinitionIds);
        $planDefinitions = $connection->fetchAssoc($select);

        $select = $connection->select()->from(
            $installer->getTable('aw_sarp2_profile'),
            ['profile_id', 'plan_definition_id']
        );
        $profileDefinitions = $connection->fetchAssoc($select);

        foreach ($profileDefinitions as $profile) {
            $planDefinitionId = $profile['plan_definition_id'];
            $profileId = $profile['profile_id'];
            if (!isset($planDefinitions[$planDefinitionId])) {
                continue;
            }

            $planDefinitionData = $planDefinitions[$planDefinitionId];
            unset($planDefinitionData['definition_id']);

            $connection->insert(
                $installer->getTable('aw_sarp2_profile_definition'),
                $planDefinitionData
            );
            $profileDefinitionId = $connection->lastInsertId('aw_sarp2_profile_definition');

            $connection->update(
                $installer->getTable('aw_sarp2_profile'),
                ['profile_definition_id' => $profileDefinitionId],
                ['profile_id = ?' => $profileId]
            );
        }
        return $this;
    }

    /**
     * Create payment sampler table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function createPaymentSamplerTable($installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_sarp2_payment_sampler'))
            ->addColumn(
                'sampler_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Payment Sampler Id'
            )->addColumn(
                'method',
                Table::TYPE_TEXT,
                128,
                ['nullable' => false],
                'Payment Method Code'
            )->addColumn(
                'status',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Status'
            )->addColumn(
                'last_transaction_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Last Transaction Id'
            )->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Store Id'
            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Customer Id'
            )->addColumn(
                'amount',
                Table::TYPE_DECIMAL,
                '12,4',
                ['default' => '0.0000'],
                'Sample Amount'
            )->addColumn(
                'amount_placed',
                Table::TYPE_DECIMAL,
                '12,4',
                ['default' => '0.0000'],
                'Placed Amount'
            )->addColumn(
                'amount_reverted',
                Table::TYPE_DECIMAL,
                '12,4',
                ['default' => '0.0000'],
                'Reverted Amount'
            )->addColumn(
                'currency_code',
                Table::TYPE_TEXT,
                255,
                [],
                'Currency Code'
            )->addColumn(
                'remote_ip',
                Table::TYPE_TEXT,
                32,
                [],
                'Remote Ip'
            )->addColumn(
                'additional_information',
                Table::TYPE_TEXT,
                '64k',
                [],
                'Additional Information'
            )->addColumn(
                'profile_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Profile Id'
            )->setComment('Payment Sampler');
        $installer->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Add payment token details field
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addPaymentTokenDetails($installer)
    {
        $installer->getConnection()->addColumn(
            $installer->getTable('aw_sarp2_payment_token'),
            'details',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'default' => '',
                'comment' => 'Token Details'
            ]
        );
        return $this;
    }

    /**
     * Add plan sort order
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addSortOrder($installer)
    {
        $installer->getConnection()->addColumn(
            $installer->getTable('aw_sarp2_plan'),
            'sort_order',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'default' => null,
                'after'   => 'name',
                'comment' => 'Sort Order'
            ]
        );

        return $this;
    }

    /**
     * Change payment token column
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function changePaymentTokenColumn($installer)
    {
        $installer->getConnection()->changeColumn(
            $installer->getTable('aw_sarp2_profile'),
            'payment_token_id',
            'payment_token_id',
            [
                'type' => Table::TYPE_INTEGER,
                'unsigned' => true,
                'nullable' => true,
                'default' => null,
                'comment' => 'Payment Token Id'
            ]
        );

        return $this;
    }

    /**
     * Add option installments mode field
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addInstallmentsModeField($installer)
    {
        $installer->getConnection()->addColumn(
            $installer->getTable('aw_sarp2_subscription_option'),
            'is_installments_mode',
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => 0,
                'after'   => 'is_auto_regular_price',
                'comment' => 'Is Installments mode Enabled'
            ]
        );

        return $this;
    }

    /**
     * Add trial period schedule different fields in plan_definition and profile_definition tables
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addTrialPeriodProfileDifferentFields($installer)
    {
        $tables = ['aw_sarp2_plan_definition', 'aw_sarp2_profile_definition'];

        $columns = [
            PlanDefinitionInterface::TRIAL_BILLING_PERIOD => [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after'   => PlanDefinitionInterface::BILLING_FREQUENCY,
                'comment' => 'Trial Billing Period'
            ],
            PlanDefinitionInterface::TRIAL_BILLING_FREQUENCY => [
                'type' => Table::TYPE_SMALLINT,
                'nullable' => true,
                'unsigned' => true,
                'after'   => PlanDefinitionInterface::TRIAL_BILLING_PERIOD,
                'comment' => 'Trial Billing Frequency'
            ],
            PlanDefinitionInterface::UPCOMING_TRIAL_BILLING_EMAIL_OFFSET => [
                'type' => Table::TYPE_SMALLINT,
                'nullable' => true,
                'unsigned' => true,
                'after' => PlanDefinitionInterface::UPCOMING_BILLING_EMAIL_OFFSET,
                'comment' => 'Upcoming trial billing email offset'
            ]
        ];

        return $this->addColumnsToTables($installer, $tables, $columns);
    }

    /**
     * Add trial period schedule different fields in core_schedule table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addTrialPeriodScheduleDifferentFields($installer)
    {
        $tables = ['aw_sarp2_core_schedule'];

        $columns = [
            ScheduleInterface::TRIAL_PERIOD => [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after'   => 'frequency',
                'comment' => 'Trial Period'
            ],
            ScheduleInterface::TRIAL_FREQUENCY => [
                'type' => Table::TYPE_SMALLINT,
                'nullable' => true,
                'unsigned' => true,
                'after'   => ScheduleInterface::TRIAL_PERIOD,
                'comment' => 'Trial Frequency'
            ]
        ];

        return $this->addColumnsToTables($installer, $tables, $columns);
    }

    /**
     * Add trial period schedule different fields in plan_definition and profile_definition tables
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addOfferExtendOptionFields($installer)
    {
        $tables = ['aw_sarp2_plan_definition', 'aw_sarp2_profile_definition'];

        $columns = [
            PlanDefinitionInterface::IS_EXTEND_ENABLE => [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => false,
                'comment' => 'Is Offer Extend enable option',
            ],
            PlanDefinitionInterface::OFFER_EXTEND_EMAIL_OFFSET => [
                'type' => Table::TYPE_SMALLINT,
                'nullable' => true,
                'unsigned' => false,
                'comment' => 'Offer Extend email offset'
            ],
            PlanDefinitionInterface::OFFER_EXTEND_EMAIL_TEMPLATE => [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Offer Extend email template',
            ],
        ];

        return $this->addColumnsToTables($installer, $tables, $columns);
    }

    /**
     * Add membership model count fields in core_schedule table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addMembershipModelCountFields($installer)
    {
        $tables = ['aw_sarp2_core_schedule'];

        $columns = [
            ScheduleInterface::MEMBERSHIP_COUNT => [
                'type' => Table::TYPE_INTEGER,
                'unsigned' => true,
                'nullable' => false,
                'default' => 0,
                'after'   => ScheduleInterface::IS_MEMBERSHIP_MODEL,
                'comment' => 'Membership Count'
            ],
            ScheduleInterface::MEMBERSHIP_TOTAL_COUNT => [
                'type' => Table::TYPE_INTEGER,
                'nullable' => false,
                'unsigned' => true,
                'default' => 0,
                'after'   => ScheduleInterface::MEMBERSHIP_COUNT,
                'comment' => 'Membership Total Count'
            ]
        ];

        return $this->addColumnsToTables($installer, $tables, $columns);
    }

    /**
     * Create access token table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function createAccessTokenTable($installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable('aw_sarp2_access_token'))
            ->addColumn(
                AccessTokenInterface::ID,
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                AccessTokenInterface::TOKEN_VALUE,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Token Value'
            )->addColumn(
                AccessTokenInterface::PROFILE_ID,
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Profile Id'
            )->addColumn(
                AccessTokenInterface::CREATED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )->addColumn(
                AccessTokenInterface::EXPIRES_AT,
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true],
                'Expires At'
            )->addColumn(
                AccessTokenInterface::ALLOWED_RESOURCE,
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Allowed resource'
            )->addForeignKey(
                $installer->getFkName(
                    'aw_sarp2_access_token',
                    'profile_id',
                    'aw_sarp2_profile',
                    'profile_id'
                ),
                'profile_id',
                $installer->getTable('aw_sarp2_profile'),
                'profile_id',
                Table::ACTION_CASCADE
            )->setComment('Access Token');
        $installer->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Add replaced by item id field into profile_item table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addReplacementItemIdField($installer)
    {
        $tables = ['aw_sarp2_profile_item'];

        $columns = [
            ProfileItemInterface::REPLACEMENT_ITEM_ID => [
                'type' => Table::TYPE_INTEGER,
                'unsigned' => true,
                'nullable' => true,
                'after'   => ProfileItemInterface::PARENT_ITEM_ID,
                'comment' => 'Replacement Item Id'
            ]
        ];

        return $this->addColumnsToTables($installer, $tables, $columns);
    }

    /**
     * Add customer_was_guest field into profile table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addWasGuestField($installer)
    {
        $tables = ['aw_sarp2_profile'];

        $columns = [
            ProfileInterface::CUSTOMER_WAS_GUEST => [
                'type' => Table::TYPE_BOOLEAN,
                'unsigned' => true,
                'default' => 0,
                'after'   => ProfileInterface::CUSTOMER_IS_GUEST,
                'comment' => 'Customer was guest'
            ]
        ];

        return $this->addColumnsToTables($installer, $tables, $columns);
    }

    /**
     * Add membership_active_until_date field into profile table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addMembershipActiveUntilField($installer)
    {
        $tables = ['aw_sarp2_profile'];

        $columns = [
            ProfileInterface::MEMBERSHIP_ACTIVE_UNTIL_DATE => [
                'type' => Table::TYPE_TIMESTAMP,
                'unsigned' => true,
                'default' => null,
                'after'   => ProfileInterface::LAST_ORDER_DATE,
                'comment' => 'Membership active until date'
            ]
        ];

        return $this->addColumnsToTables($installer, $tables, $columns);
    }

    /**
     * Add is_allow_subscription_cancellation field into profile definition table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addPlanDefinitionAllowCancellationOptionField($installer)
    {
        $tables = ['aw_sarp2_plan_definition', 'aw_sarp2_profile_definition'];

        $columns = [
            PlanDefinitionInterface::IS_ALLOW_SUBSCRIPTION_CANCELLATION => [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => true,
                'default' => null,
                'comment' => 'Is Allow Cancellation of Subscription',
            ]
        ];

        return $this->addColumnsToTables($installer, $tables, $columns);
    }

    /**
     * Add is_allow_subscription_cancellation field into profile definition table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addPrimaryKeyToPlanTitleTable($installer)
    {
        $tables = ['aw_sarp2_plan_title'];

        $columns = [
            'id' => [
                'type' => Table::TYPE_INTEGER,
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
                'comment' => 'Frontend Title Id',
                'first' => true
            ]
        ];

        return $this->addColumnsToTables($installer, $tables, $columns);
    }

    /**
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addInitialFieldsToProfileTable($installer)
    {
        $tables = ['aw_sarp2_profile'];

        $columns = [
            ProfileInterface::INITIAL_FEE => [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '0.0000',
                'comment' => 'Initial Fee Subtotal',
                'after' => ProfileInterface::BASE_TO_PROFILE_RATE
            ],
            ProfileInterface::BASE_INITIAL_FEE => [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '0.0000',
                'comment' => 'Base Initial Fee Subtotal',
                'after' => ProfileInterface::INITIAL_FEE
            ],
            ProfileInterface::INITIAL_SHIPPING_METHOD => [
                'type' => Table::TYPE_TEXT,
                'length' => '40',
                'nullable' => true,
                'comment' => 'Initial Grand Total Including Period Grand Total',
                'after' => ProfileInterface::CHECKOUT_SHIPPING_DESCRIPTION
            ],
            ProfileInterface::INITIAL_SHIPPING_DESCRIPTION => [
                'type' => Table::TYPE_TEXT,
                'length' => '255',
                'nullable' => true,
                'comment' => 'Initial Grand Total Including Period Grand Total',
                'after' => ProfileInterface::INITIAL_SHIPPING_METHOD
            ],
            ProfileInterface::INITIAL_SHIPPING_AMOUNT => [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '0.0000',
                'comment' => 'Initial Shipping Amount',
                'after' => ProfileInterface::BASE_INITIAL_TAX_AMOUNT
            ],
            ProfileInterface::BASE_INITIAL_SHIPPING_AMOUNT => [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '0.0000',
                'comment' => 'Base Initial Shipping Amount',
                'after' => ProfileInterface::INITIAL_SHIPPING_AMOUNT
            ],
            ProfileInterface::INITIAL_SHIPPING_AMOUNT_INCL_TAX => [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '0.0000',
                'comment' => 'Initial Shipping Amount Incl Tax',
                'after' => ProfileInterface::BASE_INITIAL_SHIPPING_AMOUNT
            ],
            ProfileInterface::BASE_INITIAL_SHIPPING_AMOUNT_INCL_TAX => [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '0.0000',
                'comment' => 'Base Initial Shipping Amount Incl Tax',
                'after' => ProfileInterface::INITIAL_SHIPPING_AMOUNT_INCL_TAX
            ],
            ProfileInterface::INITIAL_SHIPPING_TAX_AMOUNT => [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '0.0000',
                'comment' => 'Initial Shipping Tax Amount',
                'after' => ProfileInterface::BASE_INITIAL_SHIPPING_AMOUNT_INCL_TAX
            ],
            ProfileInterface::BASE_SHIPPING_INITIAL_TAX_AMOUNT => [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '0.0000',
                'comment' => 'Base Initial Shipping Tax Amount',
                'after' => ProfileInterface::INITIAL_SHIPPING_TAX_AMOUNT
            ]
        ];

        $this->addColumnsToTables($installer, $tables, $columns);

        $installer->getConnection()->query(
            'UPDATE ' . $installer->getTable('aw_sarp2_profile') .
            " SET
                    `initial_fee` = `initial_subtotal`,
                    `base_initial_fee` = `base_initial_subtotal`"
        );

        return $this;
    }

    /**
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addInitialFeeFieldsToProfileItemTable($installer)
    {
        // add fields
        $columns = [
            ProfileItemInterface::INITIAL_PRICE => [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '0.0000',
                'comment' => 'Initial Price',
                'after' => ProfileItemInterface::BASE_INITIAL_FEE
            ],
            ProfileItemInterface::BASE_INITIAL_PRICE => [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'nullable' => false,
                'default' => '0.0000',
                'comment' => 'Base Initial Price',
                'after' => ProfileItemInterface::INITIAL_PRICE
            ]
        ];

        $this->addColumnsToTables($installer, ['aw_sarp2_profile_item'], $columns);

        // rename fields
        $columns = [
            [
                'from' => 'initial_fee_incl_tax',
                'to' => ProfileItemInterface::INITIAL_PRICE_INCL_TAX,
                'definition' => [
                    'type' => Table::TYPE_DECIMAL,
                    'length' => '12,4',
                    'nullable' => false,
                    'default' => '0.0000',
                    'comment' => 'Initial Price Incl Tax',
                ]
            ],
            [
                'from' => 'base_initial_fee_incl_tax',
                'to' => ProfileItemInterface::BASE_INITIAL_PRICE_INCL_TAX,
                'definition' => [
                    'type' => Table::TYPE_DECIMAL,
                    'length' => '12,4',
                    'nullable' => false,
                    'default' => '0.0000',
                    'comment' => 'Base Initial Price Incl Tax',
                ]
            ],
            [
                'from' => 'initial_fee_tax_amount',
                'to' => ProfileItemInterface::INITIAL_PRICE_TAX_AMOUNT,
                'definition' => [
                    'type' => Table::TYPE_DECIMAL,
                    'length' => '12,4',
                    'nullable' => false,
                    'default' => '0.0000',
                    'comment' => 'Initial Price Tax Amount',
                ]
            ],
            [
                'from' => 'base_initial_fee_tax_amount',
                'to' => ProfileItemInterface::BASE_INITIAL_PRICE_TAX_AMOUNT,
                'definition' => [
                    'type' => Table::TYPE_DECIMAL,
                    'length' => '12,4',
                    'nullable' => false,
                    'default' => '0.0000',
                    'comment' => 'Initial Price Tax Amount',
                ]
            ],
            [
                'from' => 'initial_fee_tax_percent',
                'to' => ProfileItemInterface::INITIAL_PRICE_TAX_PERCENT,
                'definition' => [
                    'type' => Table::TYPE_DECIMAL,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Initial Price Tax Percent',
                ]
            ]
        ];

        $tableName = $installer->getTable('aw_sarp2_profile_item');
        foreach ($columns as $column) {
            if ($installer->getConnection()->tableColumnExists($tableName, $column['from'])) {
                $installer->getConnection()->changeColumn(
                    $installer->getTable('aw_sarp2_profile_item'),
                    $column['from'],
                    $column['to'],
                    $column['definition']
                );
            }
        }

        return $this;
    }
}
