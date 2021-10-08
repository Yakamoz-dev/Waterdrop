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
namespace Aheadworks\Sarp2\Model\Plan\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class BillingPeriod
 * @package Aheadworks\Sarp2\Model\Plan\Source
 */
class BillingPeriod implements ArrayInterface
{
    /**
     * 'Day' billing period
     */
    const DAY = 'day';

    /**
     * 'Week' billing period
     */
    const WEEK = 'week';

    /**
     * 'SemiMonth' billing period
     */
    const SEMI_MONTH = 'semi_month';

    /**
     * 'Month' billing period
     */
    const MONTH = 'month';

    /**
     * 'Year' billing period
     */
    const YEAR = 'year';

    /**
     * @var array
     */
    private $options;

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = [
                [
                    'value' => self::DAY,
                    'label' => __('Day'),
                    'plural_label' => __('Days')
                ],
                [
                    'value' => self::WEEK,
                    'label' => __('Week'),
                    'plural_label' => __('Weeks')
                ],
                [
                    'value' => self::SEMI_MONTH,
                    'label' => __('SemiMonth'),
                    'plural_label' => __('SemiMonths')
                ],
                [
                    'value' => self::MONTH,
                    'label' => __('Month'),
                    'plural_label' => __('Months')
                ],
                [
                    'value' => self::YEAR,
                    'label' => __('Year'),
                    'plural_label' => __('Years')
                ]
            ];
        }
        return $this->options;
    }

    /**
     * @param bool $plural
     * @return array
     */
    public function getOptions($plural = false)
    {
        $options = [];
        foreach ($this->toOptionArray() as $optionItem) {
            $field = $plural ? 'plural_label' : 'label';
            $options[$optionItem['value']] = $optionItem[$field];
        }
        return $options;
    }
}
