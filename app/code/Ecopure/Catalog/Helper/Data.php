<?php
/**
 * Copyright © 2015 Codazon . All rights reserved.
 */
namespace Ecopure\Catalog\Helper;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function getProductSkus($product)
    {
        $sku = $product->getSku();
        $type = $product->getTypeID();

        $productSkus = [$sku];
        if ($type == 'configurable') {
            $usedProducts = $product->getTypeInstance()->getUsedProducts($product);
            foreach ($usedProducts as $usedProduct) {
                $productSkus[] = $usedProduct->getSku();
            }
        }

        if ($type == 'grouped') {
            $usedProducts = $product->getTypeInstance()->getAssociatedProducts($product);
            foreach ($usedProducts as $usedProduct) {
                $productSkus[] = $usedProduct->getSku();
            }
        }

        $skus = implode(';', $productSkus);

        return $skus;
    }
}
