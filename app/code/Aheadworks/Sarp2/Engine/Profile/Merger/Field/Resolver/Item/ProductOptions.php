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
namespace Aheadworks\Sarp2\Engine\Profile\Merger\Field\Resolver\Item;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Engine\Profile\Merger\Field\ResolverInterface;

/**
 * Class ProductOptions
 * @package Aheadworks\Sarp2\Engine\Profile\Merger\Field\Resolver\Item
 */
class ProductOptions implements ResolverInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getResolvedValue($entities, $field)
    {
        /** @var ProfileItemInterface[] $entities */
        return $entities[0]->getProductOptions();
    }
}
