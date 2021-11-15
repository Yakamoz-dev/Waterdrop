<?php

/**
 * Magedelight
 * Copyright (C) 2014 Magedelight <info@magedelight.com>.
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
 * @copyright Copyright (c) 2014 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

namespace Magedelight\Bundlediscount\Block\Bundles;

use Magento\Framework\Pricing\Render\Layout;
use Magento\Framework\Serialize\SerializerInterface;

class Configure extends \Magento\Framework\View\Element\Template
{
    protected $_renderer = [];
    protected $_customOptionBlock = 'Magedelight/Bundlediscount/Bundles/Type/Options';
    protected $_customOptionsTemplate = 'bundlediscount/bundles/type/options.phtml';
    protected $_priceBlockTypes = [];
    private $serializer;

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

    /**
     * Configure constructor.
     * @param \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\App\Helper\Context $urlContext
     * @param Layout $priceLayout
     * @param array $data
     */
    public function __construct(
        \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Helper\Context $urlContext,
        SerializerInterface $serializer,
        Layout $priceLayout,
        array $data = []
    ) {
        $this->productFactory = $productFactory;
        $this->priceLayout = $priceLayout;
        $this->bundleDiscount = $bundlediscount;
        $this->serializer = $serializer;
        $this->_urlBuilder = $urlContext->getUrlBuilder();
        $this->request = $urlContext->getRequest();
        $this->urlEncoder = $urlContext->getUrlEncoder();
        parent::__construct($context, $data);

        $this->_renderer['configurable'] = ['block' => 'Magedelight\Bundlediscount\Block\Bundles\Type\Configurable',
            'template' => 'Magedelight_Bundlediscount::bundles/type/configurable.phtml'];
        $this->_renderer['bundle'] = ['block' => 'Magedelight\Bundlediscount\Block\Bundles\Type\Bundle',
            'template' => 'Magedelight_Bundlediscount::product/view/type/bundle/options.phtml',
            'actions' => [
               ['type' => 'select', 'block' => 'Magedelight\Bundlediscount\Block\Bundles\Type\Bundle\Option\Select'],
                ['type' => 'multi', 'block' => 'Magedelight\Bundlediscount\Block\Bundles\Type\Bundle\Option\Multi'],
                ['type' => 'radio', 'block' => 'Magedelight\Bundlediscount\Block\Bundles\Type\Bundle\Option\Radio'],
                ['type' => 'checkbox',
                    'block' => 'Magedelight\Bundlediscount\Block\Bundles\Type\Bundle\Option\Checkbox'],
            ],
        ];
        $this->_renderer['downloadable'] = ['block' => 'Magedelight\Bundlediscount\Block\Bundles\Type\Downloadable',
            'template' => 'Magedelight_Bundlediscount::bundles/type/downloadable.phtml'];
    }

    /**
     * @return \Magedelight\Bundlediscount\Model\Bundlediscount
     */
    public function getBundle()
    {
        $bundle = $this->bundleDiscount->load($this->getRequest()->getParam('bundle_id'));
        return $bundle;
    }

    /**
     * @return array|mixed
     */
    public function getOption()
    {
        $option = [];
        if (!empty($this->getRequest()->getParam('product_option_id'))) {
            $option = $this->getRequest()->getParam('product_option_id');
        }
        return $option;
    }

    /**
     * @return string
     */
    public function getFormActionUrl()
    {
        $routeParams = [
            \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                $this->urlEncoder->encode($this->_urlBuilder->getCurrentUrl()),
            '_secure' => $this->request->isSecure(),
            'bundle_id' => $this->getRequest()->getParam('bundle_id'),
            'product_option_id' => $this->getRequest()->getParam('product_option_id'),
        ];
        return $this->_urlBuilder->getUrl('md_bundlediscount/cart/add', $routeParams);
    }

    /**
     * @param $typeId
     * @return array|mixed
     */
    public function getRenderer($typeId)
    {
        $result = [];
        if (isset($this->_renderer[$typeId])) {
            $result = $this->_renderer[$typeId];
        }
        return $result;
    }

    /**
     * Return HTML block with tier price.
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string                         $priceType
     * @param string                         $renderZone
     * @param array                          $arguments
     *
     * @return string
     */
    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }

        /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getLayout()->createBlock(
            'Magento\Framework\Pricing\Render',
            '',
            ['data' => ['price_render_handle' => 'catalog_product_prices']]
        );
        $price = '';

        if ($priceRender) {
            $price = $priceRender->render(
                $priceType,
                $product,
                $arguments
            );
        }
        return $price;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getOptionsHtml(\Magento\Catalog\Model\Product $product)
    {
        $optionHtmlRenderer = $this->getLayout()->createBlock(
            'Magento\Catalog\Block\Product\View',
            uniqid(microtime())
        );

        $calender = $this->getLayout()->createBlock(
            'Magento\Framework\View\Element\Html\Calendar',
            uniqid(microtime())
        )->setTemplate('Magento_Theme::js/calendar.phtml');
        $optionRenderer = $this->getLayout()->createBlock(
            'Magedelight\Bundlediscount\Block\Product\View\Options',
            uniqid(microtime())
        );

        if ($optionRenderer) {
            $optionRenderer->setProduct($product);
            $optionHtmlRenderer->setProductId($product->getId());
            $defaultBlock = $this->getLayout()->createBlock('Magedelight\Bundlediscount\Block\Product\View\Options\Type\DefaultType', uniqid(microtime()))->setTemplate('Magento_Catalog::product/view/options/type/default.phtml');
            $textBlock = $this->getLayout()->createBlock('Magedelight\Bundlediscount\Block\Product\View\Options\Type\Text', uniqid(microtime()))->setTemplate('Magento_Catalog::product/view/options/type/text.phtml');
            $fileBlock = $this->getLayout()->createBlock('Magedelight\Bundlediscount\Block\Product\View\Options\Type\File', uniqid(microtime()))->setTemplate('Magento_Catalog::product/view/options/type/file.phtml');
            $selectBlock = $this->getLayout()->createBlock('Magedelight\Bundlediscount\Block\Product\View\Options\Type\Select', uniqid(microtime()))->setTemplate('Magento_Catalog::product/view/options/type/select.phtml');
            $dateBlock = $this->getLayout()->createBlock('Magedelight\Bundlediscount\Block\Product\View\Options\Type\Date', uniqid(microtime()))->setTemplate('Magento_Catalog::product/view/options/type/date.phtml');

            $optionRenderer->setChild('default', $defaultBlock);
            $optionRenderer->setChild('text', $textBlock);
            $optionRenderer->setChild('file', $fileBlock);
            $optionRenderer->setChild('select', $selectBlock);
            $optionRenderer->setChild('date', $dateBlock);

            $optionRenderer->setTemplate('Magedelight_Bundlediscount::product/view/options.phtml');

            $optionHtmlRenderer->setChild('product_options', $optionRenderer);
            $optionHtmlRenderer->setChild('html_calendar', $calender);

            $optionHtmlRenderer->setTemplate('Magento_Catalog::product/view/options/wrapper.phtml');

            return $optionHtmlRenderer->toHtml();
        }
        return '';
    }

    /**
     * @param $productId
     * @return \Magento\Catalog\Model\Product
     */
    public function getBaseProduct($productId)
    {
        return $this->productFactory->create()->load($productId);
    }


    public function getOptionId($blockOption)
    {
        return base64_encode($this->serializer->serialize($blockOption));
    }
}
