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
namespace Aheadworks\Sarp2\Model\Email\Template;

use Magento\Directory\Model\Currency;
use Magento\Directory\Model\CurrencyFactory;

/**
 * Class PriceFormatter
 * @package Aheadworks\Sarp2\Model\Email\Template
 */
class PriceFormatter
{
    /**
     * @var CurrencyFactory
     */
    private $currencyFactory;

    /**
     * @var Currency[]
     */
    private $currencyInstances = [];

    /**
     * @param CurrencyFactory $currencyFactory
     */
    public function __construct(CurrencyFactory $currencyFactory)
    {
        $this->currencyFactory = $currencyFactory;
    }

    /**
     * Format price amount
     *
     * @param float $amount
     * @param string $currencyCode
     * @return string
     */
    public function format($amount, $currencyCode)
    {
        return $this->getCurrency($currencyCode)
            ->formatPrecision($amount, 2);
    }

    /**
     * Get currency instance
     *
     * @param string $code
     * @return Currency
     */
    private function getCurrency($code)
    {
        if (!isset($this->currencyInstances[$code])) {
            $currency = $this->currencyFactory->create();
            $currency->load($code);
            $this->currencyInstances[$code] = $currency;
        }
        return $this->currencyInstances[$code];
    }
}
