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

namespace Magedelight\Bundlediscount\Block;

class Bundlecrosssell extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Tax\Model\CalculationFactory
     */
    protected $_calculationFactory;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;

    private $objectFactory;

    /**
     * Bundlecrosssell constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Tax\Model\CalculationFactory $calculationFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Locale\Format $priceFormat
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     * @param \Magedelight\Bundlediscount\Helper\Data $bundlediscountHelper
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     * @param \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount
     * @param \Magento\Framework\DataObjectFactory $objectFactory
     * @param \Magento\ConfigurableProduct\Block\Product\View\Type\ConfigurableFactory $configurable
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Tax\Model\CalculationFactory $calculationFactory,
        \Magento\Framework\Locale\Format $priceFormat,
        \Magento\Tax\Helper\Data $taxHelper,
        \Magento\Catalog\Helper\Data $catalogHelper,
        \Magedelight\Bundlediscount\Helper\Data $bundlediscountHelper,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount,
        \Magento\Framework\DataObjectFactory $objectFactory,
        \Magento\ConfigurableProduct\Block\Product\View\Type\ConfigurableFactory $configurable,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->_calculationFactory = $calculationFactory;
        $this->objectFactory = $objectFactory;
        $this->configurable = $configurable;
        $this->jsonEncoder = $jsonEncoder;
        $this->_priceFormat = $priceFormat;
        $this->_taxHelper = $taxHelper;
        $this->bundlediscountHelper = $bundlediscountHelper;
        $this->priceHelper = $priceHelper;
        $this->_catalogData = $catalogHelper;
        $this->bundleDiscount = $bundlediscount;
        parent::__construct($context, $data);
        $bundleCollection = $this->bundleDiscount->getBundlesByCustomerCrosssell();
        $this->setCollection($bundleCollection);
    }

    public function _prepareLayout()
    {
        $linkTitle = $this->bundlediscountHelper->getLinkTitle();
        $this->pageConfig->getTitle()->set(__($linkTitle));
        parent::_prepareLayout();
        return $this;
    }

    public function getLoadedBundles()
    {
        return $this->getCollection();
    }

    /**
     * @param type $product
     *
     * @return string
     */
    public function getProductJsonConfig($product)
    {
        $config = [];
        $calculator = $this->_calculationFactory->create();

        $_request = $calculator->getRateRequest(false, false, false);
        $_request->setProductClassId($product->getTaxClassId());
        $defaultTax = $calculator->getRate($_request);

        $_request = $calculator->getRateRequest();
        $_request->setProductClassId($product->getTaxClassId());
        $currentTax = $calculator->getRate($_request);

        $_regularPrice = $product->getPrice();
        $_finalPrice = $product->getFinalPrice();
        if ($product->getTypeId() == 'bundle') {
            $_priceInclTax = $this->_catalogData->getTaxPrice(
                $product,
                $_finalPrice,
                true,
                null,
                null,
                null,
                null,
                null,
                false
            );
            $_priceExclTax = $this->_catalogData->getTaxPrice(
                $product,
                $_finalPrice,
                false,
                null,
                null,
                null,
                null,
                null,
                false
            );
        } else {
            $_priceInclTax = $this->_catalogData->getTaxPrice($product, $_finalPrice, true);
            $_priceExclTax = $this->_catalogData->getTaxPrice($product, $_finalPrice);
        }
        $_tierPrices = [];
        $_tierPricesInclTax = [];
        foreach ($product->getTierPrice() as $tierPrice) {
            $_tierPrices[] = $this->priceHelper->currency(
                $this->_catalogData->getTaxPrice($product, (float) $tierPrice['website_price'], false) - $_priceExclTax,
                false,
                false
            );
            $_tierPricesInclTax[] = $this->priceHelper->currency(
                $this->_catalogData->getTaxPrice(
                    $product,
                    (float) $tierPrice['website_price'],
                    true
                ) - $_priceInclTax,
                false,
                false
            );
        }
        $config = [
            'productId' => $product->getId(),
            'priceFormat' => $this->_priceFormat->getPriceFormat(),
            'includeTax' => $this->_taxHelper->priceIncludesTax() ? 'true' : 'false',
            'showIncludeTax' => $this->_taxHelper->displayPriceIncludingTax(),
            'showBothPrices' => $this->_taxHelper->displayBothPrices(),
            'productPrice' => $this->priceHelper->currency($_finalPrice, false, false),
            'productOldPrice' => $this->priceHelper->currency($_regularPrice, false, false),
            'priceInclTax' => $this->priceHelper->currency($_priceInclTax, false, false),
            'priceExclTax' => $this->priceHelper->currency($_priceExclTax, false, false),
            /*
             * @var skipCalculate
             * @deprecated after 1.5.1.0
             */
            'skipCalculate' => ($_priceExclTax != $_priceInclTax ? 0 : 1),
            'defaultTax' => $defaultTax,
            'currentTax' => $currentTax,
            'idSuffix' => '_clone',
            'oldPlusDisposition' => 0,
            'plusDisposition' => 0,
            'plusDispositionTax' => 0,
            'oldMinusDisposition' => 0,
            'minusDisposition' => 0,
            'tierPrices' => $_tierPrices,
            'tierPricesInclTax' => $_tierPricesInclTax,
        ];

        $responseObject = $this->objectFactory->create();

        $this->_eventManager->dispatch('catalog_product_view_config', ['response_object' => $responseObject]);
        if (is_array($responseObject->getAdditionalOptions())) {
            foreach ($responseObject->getAdditionalOptions() as $option => $value) {
                $config[$option] = $value;
            }
        }
        
        return $this->jsonEncoder->encode($config);
    }

    public function getOptionsHtml(\Magento\Catalog\Model\Product $product)
    {
        $renderer = $this->configurable->create();
        if ($renderer) {
            $renderer->setProduct($product);
            $renderer->setTemplate('Magento_ConfigurableProduct::product/view/type/options/configurable.phtml');
            return $renderer->toHtml();
        }
    }

    public function isModuleEnable()
    {
        return $this->bundlediscountHelper->isEnableFrontend();
    }

    public function isEnableForCart()
    {
        return $this->bundlediscountHelper->isEnableForCart();
    }

    public function calculateDiscountAmount($bundle)
    {
        return $this->bundleDiscount->calculateDiscountAmount($bundle);
    }
}
