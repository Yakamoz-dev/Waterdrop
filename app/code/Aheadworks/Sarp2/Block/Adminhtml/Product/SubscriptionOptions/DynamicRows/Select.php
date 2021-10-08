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
namespace Aheadworks\Sarp2\Block\Adminhtml\Product\SubscriptionOptions\DynamicRows;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select as HtmlSelect;

/**
 * Class Select
 * @package Aheadworks\Sarp2\Block\Adminhtml\Product\SubscriptionOptions\DynamicRows
 */
class Select extends HtmlSelect
{
    /**
     * @var OptionSourceInterface
     */
    private $optionSource;

    /**
     * @param Context $context
     * @param OptionSourceInterface $optionSource
     * @param array $data
     */
    public function __construct(
        Context $context,
        OptionSourceInterface $optionSource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->optionSource = $optionSource;
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->optionSource->toOptionArray());
        }

        $html = parent::_toHtml();
        $html = str_replace('&quot;', "\'", $html);

        return $html;
    }

    /**
     * Sets name for input element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }
}
