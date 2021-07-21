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
namespace Aheadworks\Sarp2\Ui\DataProvider\Product\Form\Modifier;

use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Model\Product\Attribute\AttributeName as Attribute;
use Aheadworks\Sarp2\Model\Product\Attribute\Backend\SubscriptionOptions\Generator\Key as KeyGenerator;
use Aheadworks\Sarp2\Model\Product\Checker\IsChildOfConfigurable as IsChildOfConfigurableChecker;
use Aheadworks\Sarp2\Model\Product\Type\Configurable\ParentProductResolver;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;

/**
 * Class SubscriptionOptions
 * @package Aheadworks\Sarp2\Ui\DataProvider\Product\Form\Modifier
 */
class ChildOfConfigurableSubscriptionOptions extends AbstractModifier
{
    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var IsChildOfConfigurableChecker
     */
    private $isChildOfConfigurableChecker;

    /**
     * @var ParentProductResolver
     */
    private $configurableParentProductResolver;

    /**
     * @var KeyGenerator
     */
    private $keyGenerator;

    /**
     * @var array
     */
    private $keyGeneratorFields = [
        SubscriptionOptionInterface::WEBSITE_ID,
        SubscriptionOptionInterface::PLAN_ID
    ];

    /**
     * @param ArrayManager $arrayManager
     * @param LocatorInterface $locator
     * @param IsChildOfConfigurableChecker $isChildOfConfigurableChecker
     * @param ParentProductResolver $configurableParentProductResolver
     * @param KeyGenerator $keyGenerator
     */
    public function __construct(
        ArrayManager $arrayManager,
        LocatorInterface $locator,
        IsChildOfConfigurableChecker $isChildOfConfigurableChecker,
        ParentProductResolver $configurableParentProductResolver,
        KeyGenerator $keyGenerator
    ) {
        $this->arrayManager = $arrayManager;
        $this->locator = $locator;
        $this->isChildOfConfigurableChecker = $isChildOfConfigurableChecker;
        $this->configurableParentProductResolver = $configurableParentProductResolver;
        $this->keyGenerator = $keyGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $product = $this->locator->getProduct();
        if ($this->isChildOfConfigurableChecker->check($product)) {
            $subscriptionTypePath = $this->arrayManager->findPath(
                Attribute::AW_SARP2_SUBSCRIPTION_TYPE,
                $meta,
                null,
                'children'
            );
            if ($subscriptionTypePath) {
                $meta = $this->arrayManager->merge(
                    $subscriptionTypePath . '/arguments/data/config',
                    $meta,
                    [
                        'visible' => false
                    ]
                );
            }
        }

        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $product = $this->locator->getProduct();
        $productId = $product->getId();
        if ($this->isChildOfConfigurableChecker->check($product)) {

            if (isset($data[$productId][static::DATA_SOURCE_DEFAULT][Attribute::AW_SARP2_SUBSCRIPTION_OPTIONS])) {
                $parentOptionKeys = $this->getParentProductOptionKeys($product);

                foreach ($data[$productId][static::DATA_SOURCE_DEFAULT][Attribute::AW_SARP2_SUBSCRIPTION_OPTIONS]
                         as $index => $option) {
                    $childOptionKey = $this->keyGenerator->generate(
                        $option,
                        $this->keyGeneratorFields
                    );
                    if (!in_array($childOptionKey, $parentOptionKeys)) {
                        unset(
                            $data[$productId][static::DATA_SOURCE_DEFAULT]
                                [Attribute::AW_SARP2_SUBSCRIPTION_OPTIONS][$index]
                        );
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Get parent subscription option keys
     *
     * @param ProductInterface $childProduct
     * @return string[]
     */
    private function getParentProductOptionKeys($childProduct)
    {
        $keys = [];
        $parentProductSubscriptionOptions = $this->configurableParentProductResolver
            ->resolveParentProductSubscriptionOptions($childProduct->getId());
        foreach ($parentProductSubscriptionOptions as $option) {
            $keys[] = $this->keyGenerator->generate(
                $option,
                $this->keyGeneratorFields
            );
        }

        return $keys;
    }
}
