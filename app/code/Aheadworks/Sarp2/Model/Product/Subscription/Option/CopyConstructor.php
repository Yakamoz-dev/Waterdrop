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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Option;

use Magento\Catalog\Model\Product\CopyConstructorInterface;
use Magento\Catalog\Model\Product;

/**
 * Class CopyConstructor
 * @package Aheadworks\Sarp2\Model\Product\Subscription\Option
 */
class CopyConstructor implements CopyConstructorInterface
{
    /**
     * Duplicating subscription options
     *
     * @param Product $product
     * @param Product $duplicate
     * @return void
     */
    public function build(Product $product, Product $duplicate)
    {
        $options = $duplicate->getData('aw_sarp2_subscription_options') ? : [];
        foreach ($options as &$option) {
            $option['option_id'] = null;
            $option['product_id'] = null;
        }
        $duplicate->setData('aw_sarp2_subscription_options', $options);
    }
}
