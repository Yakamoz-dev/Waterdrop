<?php
/**
 * Magedelight
 * Copyright (C) 2017 Magedelight <info@magedelight.com>
 *
 * @category Magedelight
 * @package Magedelight_OneStepCheckout
 * @copyright Copyright (c) 2017 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */
namespace Magedelight\Bundlediscount\Helper;

/**
 * Default review helper
 */
class Option extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Option constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }
    
    /**
     * Get review statuses with their codes
     *
     * @return array
     */
    public function getStatuses()
    {
        return [
            \Magento\Review\Model\Review::STATUS_APPROVED => __('Enabled'),
            \Magento\Review\Model\Review::STATUS_PENDING => __('Disabled'),
        ];
    }
    
    /**
     * Get review statuses as option array
     *
     * @return array
     */
    public function getStatusesOptionArray()
    {
        $result = [];
        foreach ($this->getStatuses() as $value => $label) {
            $result[] = ['value' => $value, 'label' => $label];
        }
        return $result;
    }
}
