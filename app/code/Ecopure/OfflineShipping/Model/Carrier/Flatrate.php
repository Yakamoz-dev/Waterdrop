<?php
namespace Ecopure\OfflineShipping\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\OfflineShipping\Model\Carrier\Flatrate as Magento_Flatrate;

class Flatrate extends Magento_Flatrate {
    /**
     * Collect and get rates
     *
     * @param RateRequest $request
     * @return bool|\Magento\Shipping\Model\Rate\Result
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function collectRates(RateRequest $request)
    {
        $minSubtotal = $request->getPackageValueWithDiscount();
        if ($request->getBaseSubtotalWithDiscountInclTax()
            && $this->getConfigFlag('tax_including')) {
            $minSubtotal = $request->getBaseSubtotalWithDiscountInclTax();
        }
        if ($minSubtotal >= 20) {
            return false;
        }
        $result = parent::collectRates($request);
        return $result;
    }
}
