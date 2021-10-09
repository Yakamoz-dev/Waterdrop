<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-fraud-check
 * @version   1.1.5
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\FraudCheck\Rule\Location;

use Magento\Framework\DataObject;
use Mirasvit\FraudCheck\Api\Service\CoordinateInterface;
use Mirasvit\FraudCheck\Api\Service\IpLocationInterface;
use Mirasvit\FraudCheck\Rule\AbstractRule;

class Distance extends AbstractRule
{
    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return __('Distance');
    }

    /**
     * {@inheritdoc}
     */
    public function getFraudScore()
    {
        return $this->calculateFraudScore(-4, 4);
    }

    /**
     * @return int
     */
    public function getDefaultImportance()
    {
        return 7;
    }

    /**
     * {@inheritdoc}
     */
    public function collect()
    {
        $ip = $this->context->getIp();

        if (!$ip) {
            $this->addIndicator(0, __("Order placed from the admin panel"));

            return;
        }

        $ipLocation = $this->context->getMatchService()->getIpLocation($ip);

        $shippingCoords = $this->context->getMatchService()->getCoordinates(
            $this->context->getShippingCountry(),
            $this->context->getShippingCity(),
            $this->context->getShippingStreet(),
            $this->context->getShippingState()
        );

        $billingCoords = $this->context->getMatchService()->getCoordinates(
            $this->context->getBillingCountry(),
            $this->context->getBillingCity(),
            $this->context->getBillingStreet(),
            $this->context->getBillingState()
        );

        if (!$ipLocation) {
            $this->addIndicator(-4, __("Can't determine customer coordinates"));

            return;
        }

        if ($this->context->order->canShip()) {
            $this->addDistanceIndicator($shippingCoords, $ipLocation, 'shipping');
        } else {
            $this->addIndicator(0, __("Order contains only virtual products"));
        }

        $this->addDistanceIndicator($billingCoords, $ipLocation, 'billing');
    }

    /**
     * @param float $latA
     * @param float $lonA
     * @param float $latB
     * @param float $lonB
     * @return float
     */
    public function getDistance($latA, $lonA, $latB, $lonB)
    {
        $deltaLat = $latB - $latA;
        $deltaLon = $lonB - $lonA;

        $radius = 6372.795477598;

        $alpha = $deltaLat / 2;
        $beta = $deltaLon / 2;
        $a = sin(deg2rad($alpha)) * sin(deg2rad($alpha))
            + cos(deg2rad($latB)) * cos(deg2rad($latB)) * sin(deg2rad($beta)) * sin(deg2rad($beta));
        $c = asin(min(1, sqrt($a)));
        $distance = 2 * $radius * $c;

        return round($distance);
    }

    /**
     * @param DataObject|bool $coords
     * @param DataObject $ipLocation
     * @param string $prefix
     */
    private function addDistanceIndicator($coords, $ipLocation, $prefix)
    {
        if (!$coords) {
            $this->addIndicator(-3, __("Can't determine %1 address coordinates", $prefix));
        } else {
            /** @var IpLocationInterface $ipLocation */
            /** @var CoordinateInterface $coords */

            $distance = $this->getDistance(
                $coords->getLat(), $coords->getLng(),
                $ipLocation->getLat(), $ipLocation->getLng()
            );

            if ($distance < 100) {
                $this->addIndicator(2,
                    __("Distance between %1 address and customer location near <b>%2</b> km", $prefix, $distance));
            } else {
                $this->addIndicator(-2,
                    __("Distance between %1 address and customer location near <b>%2</b> km", $prefix, $distance));
            }
        }
    }
}
