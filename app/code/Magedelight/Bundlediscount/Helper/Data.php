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

namespace Magedelight\Bundlediscount\Helper;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\ScopeInterface;
use phpDocumentor\Reflection\Types\Self_;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const XML_PATH_ENABLE_TO_FRONTEND = 'bundlediscount/others/enable_frontend';
    const XML_PATH_TO_DISPLAY_OPTION = 'bundlediscount/others/display_options';
    const XML_PATH_TO_LINT_TITLE = 'bundlediscount/general/link_title';
    const XML_PATH_URL_KEY = 'bundlediscount/general/url_key';
    const XML_PATH_ENABLE_BUNDLE_ON = 'bundlediscount/general/enabled_bundle_on';
    const XML_PATH_URL_SUFFIX = 'bundlediscount/general/url_suffix';
    const XML_PATH_ENABLE_FOR_CART = 'bundlediscount/others/enable_bundle_cart';
    const XML_PATH_PAGE_LAYOUT = 'bundlediscount/general/page_layout';
    const XML_PATH_DISCOUNT_LABEL = 'bundlediscount/general/discount_label';
    const XML_PATH_HEADING_TITLE = 'bundlediscount/general/heading_title';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * Request object.
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    protected $urlEncoder;

    private $storeManager;
    public $productRepository;
    protected $configurableType;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Helper\Context $urlContext
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Helper\Context $urlContext,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurableType
    ) {
        $this->_urlBuilder = $urlContext->getUrlBuilder();
        $this->request = $urlContext->getRequest();
        $this->urlEncoder = $urlContext->getUrlEncoder();
        $this->storeManager = $storeManager;
        $this->productRepository = $productRepository;
        $this->configurableType = $configurableType;
        parent::__construct($context);
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isEnabled()
    {
        return $this->getConfig('bundlediscount/general/enable_link');
    }



    /**
     * @param $config_path
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $string
     * @return string
     */
    public function formatPercentage($string)
    {
        preg_match('/^\d+\.\d+$/', $string, $matches);
        if (count($matches) > 0) {
            $parts = explode('.', $string);
            $firstPart = $parts[0];
            $decimalPart = $parts[1];
            $decimalDigits = str_split($decimalPart);
            if (!isset($decimalDigits[0])) {
                $decimalDigits[0] = 0;
            }
            if (!isset($decimalDigits[1])) {
                $decimalDigits[1] = 0;
            }
            if (!isset($decimalDigits[2])) {
                $decimalDigits[2] = 0;
            }
            if (!isset($decimalDigits[3])) {
                $decimalDigits[3] = 0;
            }

            $decimalDigits[1] = ($decimalDigits[2] > 5) ? $decimalDigits[1] + 1 : $decimalDigits[1];
            $convertdString = $firstPart;
            $convertdString .= ($decimalDigits[0] == '0' && $decimalDigits[1] == '0') ? '' : '.'.$decimalDigits[0];
            $convertdString .= ($decimalDigits[1] == '0') ? '' : $decimalDigits[1];

            return $convertdString;
        }

        return $string;
    }

    /**
     * @return array
     */
    public function getStoreCodeMaps()
    {
        $result = [];
        $storeCollection = $this->storeManager->getStores(false);

        foreach ($storeCollection as $store) {
            $result[$store->getId()] = $store->getCode();
        }
        $result[0] = 'all';
        return $result;
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getDiscountLabel()
    {
        $label = $this->scopeConfig->getValue(
            self::XML_PATH_DISCOUNT_LABEL,
            ScopeInterface::SCOPE_STORE
        );

        if (is_null($label) || strlen($label) <= 0) {
            $label = __('Bundle Promotions Discount');
        }
        return $label;
    }

    /**
     * @param $bundleId
     * @return string
     */
    public function getBundleAddToCartUrl($bundleId)
    {
        $routeParams = [
            \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                $this->urlEncoder->encode($this->_urlBuilder->getCurrentUrl()),
            '_secure' => $this->request->isSecure(),
            'bundle_id' => $bundleId,
        ];

        return $this->_urlBuilder->getUrl('md_bundlediscount/cart/add', $routeParams);
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getPromotionHeading()
    {
        $title = __('Bundle Promotions');
        $config = $this->scopeConfig->getValue(
            self::XML_PATH_HEADING_TITLE,
            ScopeInterface::SCOPE_STORE
        );
        if ($config != '') {
            return $config;
        }
        return $title;
    }

    /**
     * @return mixed
     */
    public function isEnableFrontend()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ENABLE_TO_FRONTEND,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function displayOption()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_TO_DISPLAY_OPTION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getLinkTitle()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_TO_LINT_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getUrlKey()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_URL_KEY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isEnableBundleOn()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ENABLE_BUNDLE_ON,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getUrlSuffix()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_URL_SUFFIX,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function isEnableForCart()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ENABLE_FOR_CART,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getPageLayout()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_LAYOUT,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function loadMyProductUrl($pid)
    {
        return $this->productRepository->getById($pid);
    }

    public function loadConfigurableType($pid)
    {
        return $this->configurableType->getParentIdsByChild($pid);
    }
}
