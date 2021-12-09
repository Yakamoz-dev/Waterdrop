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

namespace Magedelight\Bundlediscount\Ui\DataProvider\Product\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\Store\Api\GroupRepositoryInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Ui\Component\Form;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Bundlediscount extends AbstractModifier
{
    const SORT_ORDER = 40;

    /**
     * @var string
     */
    private $dataScopeName;
    /**
     * Bundlediscount constructor.
     * @param \Magedelight\Bundlediscount\Helper\Data $helper
     */
    public function __construct(
        $dataScopeName,
        \Magedelight\Bundlediscount\Helper\Data $helper
    ) {
        $this->dataScopeName = $dataScopeName;
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $isEnabled = $this->helper->isEnableFrontend();
        if ($isEnabled == 1) {
            $meta = array_replace_recursive(
                $meta,
                [
                    'bundlediscount' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'additionalClasses' => 'admin__fieldset-product-bundles',
                                    'label' => __('Bundle Promotions'),
                                    'collapsible' => true,
                                    'componentType' => Form\Fieldset::NAME,
                                    'sortOrder' => $this->getNextGroupSortOrder(
                                        $meta,
                                        'search-engine-optimization',
                                        self::SORT_ORDER
                                    ),
                                ],
                            ],
                        ],
                        'children' => $this->getPanelChildren(),
                    ],
                ]
            );
        }
        return $meta;
    }

    /**
     * Prepares panel children configuration.
     *
     * @return array
     */
    protected function getPanelChildren()
    {
        return [
            'bundlediscount_products_button_set' => $this->getButtonSet(),
         ];
    }

    /**
     * Returns Buttons Set configuration.
     *
     * @return array
     */
    protected function getButtonSet()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'component' => 'Magedelight_Bundlediscount/js/components/container-bundlediscount-handler',
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => false,
                        'content1' => __(
                            'Create bundle promotion and apply discount.'
                        ),
                        'template' => 'ui/form/components/complex',
                        'createBundlediscountButton' => 'ns = ${ $.ns }, index = create_bundlediscount_products_button',
                    ],
                ],
            ],
            'children' => [

                'create_bundlediscount_products_button' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'container',
                                'componentType' => 'container',
                                'component' => 'Magento_Ui/js/form/components/button',
                                'actions' => [
                                    [
                                        'targetName' => $this->dataScopeName.'.bundlediscountModal',
                                        'actionName' => 'trigger',
                                        'params' => ['active', true],
                                    ],
                                    [
                                        'targetName' => $this->dataScopeName.'.bundlediscountModal',
                                        'actionName' => 'openModal',
                                    ],
                                ],
                                'title' => __('Bundle Configuration'),
                                'sortOrder' => 20,

                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
