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
namespace Aheadworks\Sarp2\Model\Product\Type\Plugin;

use Magento\Catalog\Model\Product as Product;

/**
 * Class ProductSubOptions
 * @package Aheadworks\Sarp2\Model\Product\Type\Plugin
 */
class ProductSubOptions
{
    /**
     * @param $interceptor
     * @param Product $product
     * @param array $productData
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeInitializeFromData(
        $interceptor,
        Product $product,
        array $productData
    ) {
        $productData['aw_sarp2_subscription_options'] = isset($productData['aw_sarp2_subscription_options'])
            ? $productData['aw_sarp2_subscription_options']
            : [];

        return [$product, $productData];
    }
}
