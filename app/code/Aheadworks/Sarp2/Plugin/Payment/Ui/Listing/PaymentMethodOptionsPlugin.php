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
namespace Aheadworks\Sarp2\Plugin\Payment\Ui\Listing;

use Magento\Payment\Helper\Data;
use Magento\Payment\Ui\Component\Listing\Column\Method\Options;

class PaymentMethodOptionsPlugin
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var Data
     */
    private $paymentHelper;

    /**
     * @param Data $paymentHelper
     */
    public function __construct(
        Data $paymentHelper
    ) {
        $this->paymentHelper = $paymentHelper;
    }

    /**
     * @param Options $subject
     * @param callable $proceed
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundToOptionArray(
        Options $subject,
        callable $proceed
    ) {
        if ($this->options === null) {
            $this->options = $proceed();

            $allPaymentMethods = $this->paymentHelper->getPaymentMethods();
            foreach ($allPaymentMethods as $code => $paymentMethod) {
                if (!array_key_exists($code, $this->options)) {
                    try {
                        $methodInstance = $this->paymentHelper->getMethodInstance($code);
                        $title = $methodInstance->getTitle();
                        if (is_string($title)) {
                            $this->options[$code] = [
                                'value' => $code,
                                'label' => $title
                            ];
                        }
                    } catch (\Exception $exception) {
                        continue;
                    }
                }
            }
        }

        return $this->options;
    }
}
