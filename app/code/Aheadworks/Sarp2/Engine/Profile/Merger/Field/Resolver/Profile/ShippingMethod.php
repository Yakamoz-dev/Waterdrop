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
namespace Aheadworks\Sarp2\Engine\Profile\Merger\Field\Resolver\Profile;

use Aheadworks\Sarp2\Engine\Profile\Merger\Field\ResolverInterface;
use Aheadworks\Sarp2\Model\Config;
use Aheadworks\Sarp2\Model\Profile;

/**
 * Class ShippingMethod
 * @package Aheadworks\Sarp2\Engine\Profile\Merger\Field\Resolver\Profile
 */
class ShippingMethod implements ResolverInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolvedValue($entities, $field)
    {
        $value = null;

        /** @var Profile[] $entities */
        $entitiesCount = count($entities);
        if ($entitiesCount) {
            $isSame = true;
            $baseValue = null;
            $storeId = null;

            if ($entitiesCount > 1) {
                $baseValue = $entities[0]->getDataUsingMethod($field);
                $storeId = $entities[0]->getStoreId();

                /**
                 * @param Profile $profile
                 * @param int $index
                 * @return void
                 */
                $callback = function ($profile, $index) use ($field, $baseValue, &$isSame) {
                    if ($index > 0 && $profile->getDataUsingMethod($field) != $baseValue) {
                        $isSame = false;
                    }
                };
                array_walk($entities, $callback);
            }

            $isFreeShipping = $baseValue == 'freeshipping_freeshipping';
            if ($isSame && !$isFreeShipping) {
                $value = $baseValue;
            } else {
                $defaultShippingMethod = $this->config->getDefaultShippingMethod($storeId);
                if ($defaultShippingMethod) {
                    $value = $defaultShippingMethod;
                }
            }
        }

        return $value;
    }
}
