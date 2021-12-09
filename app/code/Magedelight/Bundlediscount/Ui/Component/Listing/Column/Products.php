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

namespace Magedelight\Bundlediscount\Ui\Component\Listing\Column;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Products extends Column
{
    /**
     * Products constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magedelight\Bundlediscount\Model\Bundlediscount $bundlediscount,
        array $components = [],
        array $data = []
    ) {
        $this->bundleDiscount = $bundlediscount;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')] = $this->prepareItem($item);
            }
        }
        return $dataSource;
    }

    /**
     * Get data.
     *
     * @param array $item
     *
     * @return string
     */
    protected function prepareItem(array $item)
    {
        $product_id = $item['product_id'];
        if (empty($product_id)) {
            return '';
        }

        $bundle = $this->bundleDiscount->load($item['bundle_id']);
        $string = '';
        $string .= '<strong>'.__('Name').':</strong>'.$bundle->getProductName().'<br />';
        $string .= '<strong>'.__('SKU').':</strong>'.$bundle->getProductSku().'<br />';
        $string .= '<strong>'.__('Qty').':</strong>'.$bundle->getQty().'<br />';
        $string .= '<br /><br />';

        if ($bundle->getSelections() != null) {
            foreach ($bundle->getSelections() as $_selection) {
                $string .= '<strong>'.__('Name').':</strong>'.$_selection->getName().'<br />';
                $string .= '<strong>'.__('SKU').':</strong>'.$_selection->getSku().'<br />';
                $string .= '<strong>'.__('Qty').':</strong>'.$_selection->getQty().'<br />';
            }
        }
        return $string;
    }
}
