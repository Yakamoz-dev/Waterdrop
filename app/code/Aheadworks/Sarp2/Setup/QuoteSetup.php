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

use Magento\Quote\Setup\QuoteSetup as MagentoQuoteSetup;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class QuoteSetup
 *
 * @package Aheadworks\Sarp2\Setup
 */
class QuoteSetup extends MagentoQuoteSetup
{
    /**
     * Add quote totals
     *
     * @param string $entityTypeId
     * @param array $totals
     * @param array $totalGroups
     * @param array $totalPrefixes
     * @return void
     */
    public function addQuoteTotals(
        $entityTypeId,
        $totals,
        $totalGroups = ['initial', 'trial', 'regular'],
        $totalPrefixes = ['aw_sarp', 'base_aw_sarp']
    ) {
        foreach ($totals as $total) {
            foreach ($totalGroups as $group) {
                foreach ($totalPrefixes as $prefix) {
                    $this->addAttribute(
                        $entityTypeId,
                        $prefix . '_' . $group . '_' . $total,
                        ['type' => Table::TYPE_DECIMAL]
                    );
                }
            }
        }
    }

    /**
     * Remove quote totals
     *
     * @param string $entityTypeId
     * @param array $totals
     * @param array $totalGroups
     * @param array $totalPrefixes
     * @return void
     */
    public function removeQuoteTotals(
        $entityTypeId,
        $totals,
        $totalGroups = ['initial', 'trial', 'regular'],
        $totalPrefixes = ['aw_sarp', 'base_aw_sarp']
    ) {
        foreach ($totals as $total) {
            foreach ($totalGroups as $group) {
                foreach ($totalPrefixes as $prefix) {
                    $this->removeAttribute(
                        $entityTypeId,
                        $prefix . '_' . $group . '_' . $total
                    );
                }
            }
        }
    }

    /**
     * Remove entity attribute. Overwritten for flat entities support
     *
     * @param int|string $entityTypeId
     * @param string $code
     * @return $this
     */
    public function removeAttribute($entityTypeId, $code)
    {
        if (isset($this->_flatEntityTables[$entityTypeId])
            && $this->_flatTableExist($this->_flatEntityTables[$entityTypeId])
        ) {
            $this->removeFlatAttribute($this->_flatEntityTables[$entityTypeId], $code);
        } else {
            parent::removeAttribute($entityTypeId, $code);
        }

        return $this;
    }

    /**
     * Remove attribute as separate column in the table
     *
     * @param string $table
     * @param string $attribute
     * @return $this
     */
    protected function removeFlatAttribute($table, $attribute)
    {
        $tableInfo = $this->getConnection()->describeTable($this->getTable($table));
        if (isset($tableInfo[$attribute])) {
            $this->getConnection()->dropColumn($this->getTable($table), $attribute);
        }

        return $this;
    }
}
