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
namespace Aheadworks\Sarp2\Engine\Profile\Merger\Set;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileAddressInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;

/**
 * Class DataResolver
 * @package Aheadworks\Sarp2\Engine\Profile\Merger\Set
 */
class DataResolver
{
    /**
     * Get all profiles items
     *
     * @param ProfileInterface[] $profiles
     * @return ProfileItemInterface[]
     */
    public function getItems($profiles)
    {
        $allItems = [];

        /**
         * @param ProfileInterface $profile
         * @return void
         */
        $callback = function ($profile) use (&$allItems) {
            foreach ($profile->getItems() as $item) {
                $allItems[$item->getItemId()] = $item;
            }
        };
        array_walk($profiles, $callback);
        return $allItems;
    }

    /**
     * Get profile addresses of specified address type
     *
     * @param ProfileInterface[] $profiles
     * @param $addressType
     * @return ProfileAddressInterface[]
     */
    public function getAddresses($profiles, $addressType)
    {
        /**
         * @param ProfileInterface $profile
         * @return ProfileAddressInterface
         */
        $closure = function ($profile) use ($addressType) {
            return $addressType == 'shipping'
                ? $profile->getShippingAddress()
                : $profile->getBillingAddress();
        };
        return array_map($closure, $profiles);
    }

    /**
     * Check if a set correspond to a virtual profile
     *
     * @param ProfileInterface[] $profiles
     * @return bool
     */
    public function isVirtual(array $profiles)
    {
        $isVirtual = true;
        foreach ($profiles as $profile) {
            if (!$profile->getIsVirtual()) {
                $isVirtual = false;
            }
        }
        return $isVirtual;
    }
}
