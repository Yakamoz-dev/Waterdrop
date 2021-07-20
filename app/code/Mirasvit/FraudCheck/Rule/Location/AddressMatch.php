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
 * @version   1.1.4
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\FraudCheck\Rule\Location;

use Mirasvit\FraudCheck\Rule\AbstractRule;

class AddressMatch extends AbstractRule
{
    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return __('Customer Location');
    }

    /**
     * {@inheritdoc}
     */
    public function getFraudScore()
    {
        return $this->calculateFraudScore(-2, 2);
    }

    /**
     * @return int
     */
    public function getDefaultImportance()
    {
        return 9;
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

        $ipCountry = $ipLocation ? $ipLocation->getCountryCode() : '';
        $billingCountry = $this->context->getBillingCountry();
        $shippingCountry = $this->context->getShippingCountry();

        if (!$ipCountry) {
            $this->addIndicator(-2, __("Can't determine country for IP: %1", $ip));

            return;
        }

        $this->addAddressIndicator($billingCountry, $ipCountry, 'billing');
        $this->addAddressIndicator($shippingCountry, $ipCountry, 'shipping');

        return;
    }

    /**
     * @param string $country
     * @param string $ipCountry
     * @param string $prefix
     */
    private function addAddressIndicator($country, $ipCountry, $prefix)
    {
        if ($country) {
            if ($ipCountry == $country) {
                $this->addIndicator(1,
                    __('Order placed from %1 and %2 address is in %3', $ipCountry, $prefix, $country));
            } else {
                $this->addIndicator(-1,
                    __('Order placed from %1, but %2 address is in %3', $ipCountry, $prefix, $country));
            }
        } elseif ($country) {
            $this->addIndicator(-1,
                __("%1 country is not set", ucfirst($prefix), $country));
        }
    }
}
