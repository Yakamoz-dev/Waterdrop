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
namespace Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector\Shipping;

use Aheadworks\Sarp2\Api\Data\ProfileAddressInterface;
use Aheadworks\Sarp2\Model\Profile\Address\Resolver\Region;

/**
 * Class AddressDataResolver
 * @package Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector\Shipping
 */
class AddressDataResolver
{
    /**
     * @var Region
     */
    private $regionResolver;

    /**
     * @param Region $regionResolver
     */
    public function __construct(Region $regionResolver)
    {
        $this->regionResolver = $regionResolver;
    }

    /**
     * Get region code
     *
     * @param ProfileAddressInterface $address
     * @return string
     */
    public function getRegionCode($address)
    {
        return $this->regionResolver->getRegionCode(
            $address->getRegionId(),
            $address->getRegion(),
            $address->getCountryId()
        );
    }

    /**
     * Get full street
     *
     * @param ProfileAddressInterface $address
     * @return string
     */
    public function getFullStreet($address)
    {
        $street = $address->getStreet();
        return is_array($street)
            ? implode("\n", $street)
            : $street;
    }
}
