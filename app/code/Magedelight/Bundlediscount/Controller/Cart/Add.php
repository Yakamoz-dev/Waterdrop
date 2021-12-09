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

namespace Magedelight\Bundlediscount\Controller\Cart;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Serialize\Serializer\Json;

class Add extends \Magento\Checkout\Controller\Cart\Add
{
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $localeResolver;
    private $unserialize;
    protected $cartData;
    protected $productUrl;
    protected $outputHelper;
    protected $checkoutHelper;
    /**
     * Add constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Catalog\Model\Product\OptionFactory $optionFactory
     * @param \Magedelight\Bundlediscount\Model\BundlediscountFactory $bundlediscountFactory
     * @param \Magento\Bundle\Model\OptionFactory $bundleOption
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param CustomerCart $cart
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Catalog\Model\Product\OptionFactory $optionFactory,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magedelight\Bundlediscount\Model\BundlediscountFactory $bundlediscountFactory,
        \Magento\Bundle\Model\OptionFactory $bundleOption,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Framework\DataObjectFactory $objectFactory,
        SerializerInterface $serializer,
        CustomerCart $cart,
        ProductRepositoryInterface $productRepository,
        \Magento\Checkout\CustomerData\Cart $cartData,
        \Magento\Catalog\Model\Product\Url $productUrl,
        \Magento\Catalog\Helper\Output $outputHelper,
        \Magento\Checkout\Helper\Data $checkoutHelper
    ) {
        $this->localeResolver = $localeResolver;
        $this->bundleDiscount = $bundlediscountFactory;
        $this->_optionModel = $optionFactory->create();
        $this->_bundleOption = $bundleOption->create();
        $this->resultJsonFactory = $jsonFactory;
        $this->objectFactory = $objectFactory;
        $this->serializer = $serializer;
        $this->cartData = $cartData;
        $this->productUrl = $productUrl;
        $this->outputHelper = $outputHelper;
        $this->checkoutHelper = $checkoutHelper;
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart,
            $productRepository
        );
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $response = $this->objectFactory->create();
        $error = false;
        $backUrl = $this->_storeManager->getStore()->getBaseUrl().'checkout/cart';

        $bundleMessages = [];
        $posts = $this->getRequest()->getPost();

        foreach ($posts as $key => $option) {
            if ($key == 'super_attribute') {
                $productId = key($option);
                $opType = $key;

                if ($opType == 'super_attribute') {
                    foreach ($option as $k => $opt) {
                        if (is_array($opt)) {
                            foreach ($opt as $ke => $o) {
                                $attrId = $ke;
                                $optionArr[$k]['super_attribute'][$attrId] = $o;
                            }
                        } else {
                            $attrId = key($opt);
                            $optionArr[$k]['super_attribute'][$attrId] = $opt[$attrId];
                        }
                    }
                }
            } elseif ($key == 'options') {
                foreach ($option as $optId => $opt) {
                    $optionValues = $this->_optionModel->load($optId);
                    $productId = $optionValues->getData('product_id');
                    $opType = $optionValues->getType();

                    if (in_array($opType, ['field', 'area', 'drop_down', 'radio'])) {
                        $optionArr[$productId]['options'][$optId] = $opt;
                    } elseif (in_array($opType, ['checkbox', 'multiple'])) {
                        $optionArr[$productId]['options'][$optId] = $opt;
                    } elseif (in_array($opType, ['date', 'date_time', 'time'])) {
                        foreach ($opt as $valueType => $value) {
                            $optionArr[$productId]['options'][$optId][$valueType] = $value;
                        }
                    }
                }
            } elseif (isset($key) && in_array($key, ['bundle_option', 'bundle_option_qty'])) {
                foreach ($option as $optKey => $opt) {
                    $optionId = $optKey;
                    $bundleOp = $this->_bundleOption->load($optionId);
                    $productId = $bundleOp->getData('parent_id');
                    $opType = 'bundle_option';

                    $optionArr[$productId][$key][$optionId] = $opt;
                }
            } elseif ($key == 'links') {
                $productId = key($option);

                foreach ($option as $k => $opt) {
                    if (is_array($opt)) {
                        foreach ($opt as $ke => $o) {
                            $attrId = $ke;
                            $optionArr[$k]['links'][$attrId] = $o;
                        }
                    } else {
                        $attrId = key($opt);
                        $optionArr[$k]['links'][$attrId] = $opt[$attrId];
                    }
                }
            }
        }

        $postParams = $this->getRequest()->getParams();
        if (!is_array($postParams['product_option_id'])) {
            $product_option_id = $this->serializer->unserialize(base64_decode($postParams['product_option_id']));
        } else {
            $product_option_id = ($postParams['product_option_id']);
        }
        $products = $this->_getProductsAndQtys($postParams['bundle_id']);

        $filter = new \Zend_Filter_LocalizedToNormalized(
            ['locale' => $this->localeResolver->getLocale()]
        );

        try {
            foreach ($products as $productId => $qty) {
                if (in_array($productId, $product_option_id)) {
                    $productParams['qty'] = $filter->filter($qty);
                    $product = $this->initProduct($productId);
                    $params = ['qty' => $qty, 'super_attribute' => [], 'options' => []];
                    if (isset($optionArr[$productId])) {
                        if (isset($optionArr[$productId]['options'])) {
                            $params['options'] = $optionArr[$productId]['options'];
                        }
                        if (isset($optionArr[$productId]['super_attribute'])) {
                            $params['super_attribute'] = $optionArr[$productId]['super_attribute'];
                        }
                        if (isset($optionArr[$productId]['links'])) {
                            $params['links'] = $optionArr[$productId]['links'];
                        }
                        if (isset($optionArr[$productId]['bundle_option'])) {

                            $params['bundle_option'] = $optionArr[$productId]['bundle_option'];
                            $params['bundle_option_qty'] = isset($params['qty']) ? ($params['qty']) :1 ;
                        }
                    }

                    $this->cart->addProduct($product, $params);
                    $this->_eventManager->dispatch(
                        'checkout_cart_add_product_complete',
                        ['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
                    );
                    if (!$this->_checkoutSession->getNoCartRedirect(true)) {
                        if (!$this->cart->getQuote()->getHasError()) {
                            $bundleMessages[] = __(
                                'You added %1 to your shopping cart.',
                                $product->getName()
                            );
                        }
                    }
                }
            }

            $this->_eventManager->dispatch(
                'bundlediscount_bundle_add_ids',
                ['bundle_id' => $postParams['bundle_id'], 'cart' => $this->cart]
            );

            $this->cart->save();

            if (!$this->_checkoutSession->getNoCartRedirect(true)) {
                if (!$this->cart->getQuote()->getHasError()) {
                    foreach ($bundleMessages as $bundleMessage) {
                        $this->messageManager->addSuccessMessage($bundleMessage);
                    }
                }
                return $this->goBack($backUrl, $product, $params);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
            $response->setUrl($backUrl);
            $response->setError(true);
            $response->setMessage($e->getMessage());
            $resultJson->setJsonData($response->toJson());
            return $resultJson;

        } catch (\Exception $e) {
            $response->setUrl($backUrl);
            $response->setError(true);
            $response->setMessage($e->getMessage());
            $resultJson->setJsonData($response->toJson());
            return $resultJson;

        }
    }

    /**
     * Resolve response
     *
     * @param string $backUrl
     * @param \Magento\Catalog\Model\Product $product
     * @return ResponseInterface|ResultInterface
     */
    protected function goBack($backUrl = null, $product = null, $params = null)
    {
        if (!$this->getRequest()->isAjax()) {
            return parent::_goBack($backUrl);
        }

        $result = [];

        if ($backUrl || $backUrl = $this->getBackUrl()) {
            $result['backUrl'] = $backUrl;
        } else {
            if ($product && !$product->getIsSalable()) {
                $result['product'] = [
                    'statusText' => __('Out of stock')
                ];
            }
        }

        if ($product) {
            $crosssellHtml = '';
            $result['product'] = [
                'success' => true,
                'img' => (string)$this->imgHelper->init($product, 'product_page_image_large')->resize(100,100)->getUrl(),
                'name' => $this->outputHelper->productAttribute($product, $product->getName(), 'name'),
                'id' => $product->getId(),
                'url' => $this->productUrl->getUrl($product),
                'params' => $params,
                'price' => $this->checkoutHelper->formatPrice($product->getFinalPrice())
            ];
            $result['crosssell'] = ['html' => $crosssellHtml];
            $result['cart'] = $this->cartData->getSectionData();
        }
        $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result)
        );
    }


    /**
     * @param $posts
     * @return array
     */
    protected function _prepareOptions($posts)
    {
        $params = [];
        foreach ($posts as $key => $array) {
            foreach ($array as $id => $option) {
                $params[$id][$key] = $option;
            }
        }
        return $params;
    }

    /**
     * @param $bundleId
     * @return array
     */
    protected function _getProductsAndQtys($bundleId)
    {
        $qtyArray = [];
        $bundle = $this->bundleDiscount->create()->load($bundleId);
        $selections = $bundle->getSelections();
        $qtyArray[$bundle->getProductId()] = $bundle->getQty();
        foreach ($selections as $_selection) {
            $qtyArray[$_selection->getProductId()] = $_selection->getQty();
        }
        return $qtyArray;

    }

    /**
     * Initialize product instance from request data.
     *
     * @return \Magento\Catalog\Model\Product|false
     */
    protected function initProduct($productId)
    {
        if ($productId) {
            $storeId = $this->_storeManager->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }
}
