<?php
/**
 * Magedelight
 * Copyright (C) 2017 Magedelight <info@magedelight.com>
 *
 * @category Magedelight
 * @package Magedelight_Bundlediscount
 * @copyright Copyright (c) 2017 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

namespace Magedelight\Bundlediscount\Block\Adminhtml\Tagwrapper;

use Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\CollectionFactory as Tagwrappers;

class Collection extends \Magento\Backend\Block\Template
{
    private $tagwrappersFactory;

    /**
     * Collection constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Directory\Model\Currency $currency
     * @param Tagwrappers $tagwrappersFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Directory\Model\Currency $currency,
        \Magedelight\Bundlediscount\Helper\Data $helperData,
        Tagwrappers $tagwrappersFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_currency = $currency;
        $this->helperData = $helperData;
        $this->tagwrapperFactory = $tagwrappersFactory;
    }

    /**
     * check is enable magedelight bundle discount
     * @return bool
     */
    public function isEnabled()
    {
        return $this->helperData->isEnableFrontend();
    }

    /**
     * @return \Magedelight\Bundlediscount\Model\ResourceModel\Tagwrapper\Collection
     */
    public function getTagwrapperCollection()
    {
        if ($this->isEnabled()) {
            $categoryId = $this->getRequest()->getParam('category');
            $gwcollection = $this->tagwrapperFactory->create();
            $gwcollection->addFieldToFilter('category', $categoryId);
            $gwcollection->addFieldToFilter('is_active', 1);
            return $gwcollection;
        }
    }

    /**
     * return the currency symbol
     * @return string
     */
    public function getCurrencySymbol()
    {
        $currencySymbol = $this->_currency->getCurrencySymbol();
        return $currencySymbol;
    }
}
