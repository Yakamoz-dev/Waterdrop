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
namespace Aheadworks\Sarp2\Block\Adminhtml\Plugin\Order\Create\Search;

use Aheadworks\Sarp2\Model\Product\Attribute\Source\SubscriptionType;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Sales\Block\Adminhtml\Order\Create\Search\Grid as SearchGrid;

/**
 * Class Grid
 * @package Aheadworks\Sarp2\Block\Adminhtml\Plugin\Order\Create\Search
 */
class Grid
{
    /**
     * @param SearchGrid $grid
     * @param Collection $collection
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSetCollection(
        SearchGrid $grid,
        $collection
    ) {
        $collection->addAttributeToFilter(
            'aw_sarp2_subscription_type',
            [
                'or' => [
                    ['neq' => SubscriptionType::SUBSCRIPTION_ONLY],
                    ['is' => new \Zend_Db_Expr('null')]
                ]
            ],
            'left'
        );
        return [$collection];
    }
}
