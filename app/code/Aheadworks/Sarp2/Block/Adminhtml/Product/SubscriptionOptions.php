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
namespace Aheadworks\Sarp2\Block\Adminhtml\Product;

use Aheadworks\Sarp2\Block\Adminhtml\Product\SubscriptionOptions\DynamicRows;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Escaper;

/**
 * Class SubscriptionOptions
 * @package Aheadworks\Sarp2\Block\Adminhtml\Product
 */
class SubscriptionOptions extends \Magento\Framework\Data\Form\Element\Text
{
    /**
     * @var DynamicRows
     */
    private $dynamicRows;

    /**
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param DynamicRows $dynamicRows
     * @param array $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        DynamicRows $dynamicRows,
        $data = []
    ) {
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
        $this->dynamicRows = $dynamicRows;
    }

    /**
     * {@inheritdoc}
     */
    public function getElementHtml()
    {
        $this->dynamicRows->setElement($this);
        $html = $this->dynamicRows->toHtml();

        return $html;
    }
}
