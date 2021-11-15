<?php
/**
 * Magedelight
 * Copyright (C) 2016 Magedelight <info@magedelight.com>.
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @category Magedelight
 *
 * @copyright Copyright (c) 2016 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

/**
 * Product options block.
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */

namespace Magedelight\Bundlediscount\Block\Product\View;

use Magento\Catalog\Model\Product;
use Magento\Framework\DataObjectFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Options extends \Magento\Catalog\Block\Product\View\Options
{
    /**
     * Options constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param \Magento\Catalog\Helper\Data $catalogData
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param Product\Option $option
     * @param \Magento\Framework\Registry $registry
     * @param DataObjectFactory $dataObjectFactory
     * @param \Magento\Framework\Stdlib\ArrayUtils $arrayUtils
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        Product\Option $option,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        array $data = []
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->_localeFormat = $localeFormat;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->_registry = $registry;
        parent::__construct(
            $context,
            $pricingHelper,
            $catalogData,
            $jsonEncoder,
            $option,
            $registry,
            $arrayUtils,
            $data
        );
    }

    public function getPriceJsonConfig()
    {
        $product = $this->getProduct();
        if (!$this->_registry->registry('current_product')) {
            $this->_registry->register('current_product', $product);
        }

        if (!$this->hasOptions()) {
            $config = [
                'productId' => $product->getId(),
                'priceFormat' => $this->_localeFormat->getPriceFormat(),
                ];

            return $this->_jsonEncoder->encode($config);
        }

        $tierPrices = [];
        $tierPricesList = $product->getPriceInfo()->getPrice('tier_price')->getTierPriceList();
        foreach ($tierPricesList as $tierPrice) {
            $tierPrices[] = $this->priceCurrency->convert($tierPrice['price']->getValue());
        }
        $config = [
            'productId' => $product->getId(),
            'priceFormat' => $this->_localeFormat->getPriceFormat(),
            'prices' => [
                'oldPrice' => [
                    'amount' => $this->priceCurrency->convert(
                        $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue()
                    ),
                    'adjustments' => [],
                ],
                'basePrice' => [
                    'amount' => $this->priceCurrency->convert(
                        $product->getPriceInfo()->getPrice('final_price')->getAmount()->getBaseAmount()
                    ),
                    'adjustments' => [],
                ],
                'finalPrice' => [
                    'amount' => $this->priceCurrency->convert(
                        $product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue()
                    ),
                    'adjustments' => [],
                ],
            ],
            'idSuffix' => '_clone',
            'tierPrices' => $tierPrices,
        ];

        $responseObject = $this->dataObjectFactory->create();
        $this->_eventManager->dispatch(
            'catalog_product_view_config',
            ['response_object' => $responseObject]
        );
        if (is_array($responseObject->getAdditionalOptions())) {
            foreach ($responseObject->getAdditionalOptions() as $option => $value) {
                $config[$option] = $value;
            }
        }
        return $this->_jsonEncoder->encode($config);
    }
}
