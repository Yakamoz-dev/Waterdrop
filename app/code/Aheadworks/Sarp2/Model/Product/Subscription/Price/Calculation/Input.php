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
namespace Aheadworks\Sarp2\Model\Product\Subscription\Price\Calculation;

use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;

/**
 * Class Input
 */
class Input
{
    /**
     * @var ProductInterface
     */
    private $product;

    /**
     * @var float
     */
    private $qty;

    /**
     * @var ProductInterface
     */
    private $childProduct;

    /**
     * @var float
     */
    private $childQty;

    /**
     * Input constructor.
     *
     * @param ProductInterface $product
     * @param float $qty
     * @param ProductInterface|null $childProduct
     * @param float|null $childQty
     */
    public function __construct(
        ProductInterface $product,
        float $qty,
        ProductInterface $childProduct = null,
        float $childQty = null
    ) {
        $this->product = $product;
        $this->qty = $qty;
        $this->childProduct = $childProduct;
        $this->childQty = $childQty;
    }

    /**
     * @return ProductInterface|Product
     */
    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    /**
     * @return float
     */
    public function getQty(): float
    {
        return $this->qty;
    }

    /**
     * @return ProductInterface|Product
     */
    public function getChildProduct()
    {
        return $this->childProduct;
    }

    /**
     * @return float
     */
    public function getChildQty()
    {
        return $this->childQty;
    }

    /**
     * Check if children calculated price type
     *
     * @return bool
     */
    public function isChildrenCalculated()
    {
        return null != $this->childProduct;
    }
}
