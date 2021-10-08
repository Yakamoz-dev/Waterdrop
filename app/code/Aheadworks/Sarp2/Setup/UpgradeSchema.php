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
namespace Aheadworks\Sarp2\Setup;

use Aheadworks\Sarp2\Setup\Updater\Shema\Updater;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Aheadworks\Sarp2\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var Updater
     */
    private $updater;

    /**
     * @param Updater $updater
     */
    public function __construct(Updater $updater)
    {
        $this->updater = $updater;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if ($context->getVersion() && version_compare($context->getVersion(), '2.2.0', '<')) {
            $this->updater->upgradeTo220($setup);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '2.3.0', '<')) {
            $this->updater->upgradeTo230($setup);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '2.4.0', '<')) {
            $this->updater->upgradeTo240($setup);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '2.5.0', '<')) {
            $this->updater->upgradeTo250($setup);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '2.6.0', '<')) {
            $this->updater->upgradeTo260($setup);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '2.7.0', '<')) {
            $this->updater->upgradeTo270($setup);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '2.9.0', '<')) {
            $this->updater->upgradeTo290($setup);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '2.10.0', '<')) {
            $this->updater->upgradeTo2100($setup);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '2.11.2', '<')) {
            $this->updater->upgradeTo2_11_2($setup);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '2.12', '<')) {
            $this->updater->upgradeTo2_12($setup);
        }
        if ($context->getVersion() && version_compare($context->getVersion(), '2.15.0', '<')) {
            $this->updater->upgradeTo2_15_0($setup);
        }
    }
}
