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
namespace Aheadworks\Sarp2\Model\Shipping\RateRequest;

use Aheadworks\Sarp2\Api\Data\ProfileAddressInterface;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\RateRequest;

/**
 * Class Copy
 * @package Aheadworks\Sarp2\Model\Shipping\RateRequest
 */
class Copy
{
    /**
     * Copy address to rate request
     *
     * @param Address|ProfileAddressInterface $address
     * @param RateRequest $request
     * @param array $map
     * @return RateRequest
     */
    public function copyAddress($address, $request, $map)
    {
        foreach ($map as $addressGetter => $requestSetter) {
            $value = $address->$addressGetter();
            $request->$requestSetter($value);
        }
        return $request;
    }
}
