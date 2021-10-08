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
namespace Aheadworks\Sarp2\Model\Payment\Details;

use Magento\Payment\Model\CcConfigProvider;

/**
 * Class CreditCardIconResolver
 *
 * @package Aheadworks\Sarp2\Model\Payment\Details
 */
class CreditCardIconResolver implements IconResolverInterface
{
    /**
     * @var CcConfigProvider
     */
    private $creditCardIconsProvider;

    /**
     * @param CcConfigProvider $creditCardIconsProvider
     */
    public function __construct(
        CcConfigProvider $creditCardIconsProvider
    ) {
        $this->creditCardIconsProvider = $creditCardIconsProvider;
    }

    /**
     * Retrieve icon data array for specific credit card type
     *
     * @param string $paymentType
     * @return array
     */
    public function getIconData($paymentType)
    {
        if (isset($this->creditCardIconsProvider->getIcons()[$paymentType])) {
            return $this->creditCardIconsProvider->getIcons()[$paymentType];
        }

        return [
            'url' => '',
            'width' => 0,
            'height' => 0
        ];
    }
}
