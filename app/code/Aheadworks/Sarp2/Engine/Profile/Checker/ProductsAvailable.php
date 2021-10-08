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
namespace Aheadworks\Sarp2\Engine\Profile\Checker;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;

/**
 * Class ProductsAvailable
 */
class ProductsAvailable
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Validate products available
     *
     * @param ProfileInterface $profile
     * @return bool
     */
    public function check(ProfileInterface $profile)
    {
        $isAllowed = true;
        foreach ($profile->getItems() as $item) {
            try {
                if (is_array($item)) {
                    $productId = $item[ProfileItemInterface::PRODUCT_ID] ?? 0;
                } else {
                    $productId = $item->getProductId();
                }
                /** @var Product $product */
                $product = $this->productRepository->getById(
                    $productId,
                    false,
                    $profile->getStoreId()
                );
                $isAllowed = $product->isSalable();
            } catch (\Exception $exception) {
                $isAllowed = false;
            }

            if (!$isAllowed) {
                break;
            }
        }

        return $isAllowed;
    }
}
