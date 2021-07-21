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
namespace Aheadworks\Sarp2\Model\Product\Checker;

use Aheadworks\Sarp2\Model\Product\Type\Configurable\ParentProductResolver;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;

/**
 * Class IsChildOfConfigurable
 *
 * @package Aheadworks\Sarp2\Model\Product\Checker
 */
class IsChildOfConfigurable
{
    /**
     * @var ParentProductResolver
     */
    private $configurableParentProductResolver;

    /**
     * @param ParentProductResolver $configurableParentProductResolver
     */
    public function __construct(
        ParentProductResolver $configurableParentProductResolver
    ) {
        $this->configurableParentProductResolver = $configurableParentProductResolver;
    }

    /**
     * Check if product is child of configurable
     *
     * @param ProductInterface $product
     * @return bool
     */
    public function check($product)
    {
        if ($product->getTypeId() != ConfigurableType::TYPE_CODE) {
            $parentProduct = $this->configurableParentProductResolver->resolveParentProduct($product->getId());
            return $parentProduct != null;
        }

        return false;
    }
}
