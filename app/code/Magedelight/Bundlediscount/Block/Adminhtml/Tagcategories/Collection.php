<?php
/**
 * Magedelight
 * Copyright (C) 2017 Magedelight <info@magedelight.com>
 *
 * @category Magedelight
 * @package Magedelight_Giftwrapper
 * @copyright Copyright (c) 2017 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

namespace Magedelight\Bundlediscount\Block\Adminhtml\Giftcategories;

use Magedelight\Bundlediscount\Model\ResourceModel\Tagcategories\CollectionFactory as Tagcategories;

class Collection extends \Magento\Backend\Block\Template
{

    /**
     * Collection constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magedelight\Bundlediscount\Helper\Data $helper
     * @param Tagcategories $tagcategoriesFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magedelight\Bundlediscount\Helper\Data $helper,
        Tagcategories $tagcategoriesFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->_tagcategoriesFactory = $tagcategoriesFactory;
    }

    /**
     * check if Giftwrapper needs to be displayed at product view page and extension is enabled.
     * @return true/false
     */

    public function isEnabled()
    {
        return $this->helper->isEnableFrontend();
    }

    /**
     * Retrieve tag Categories object and create collection
     *
     * @return giftwrappercollection
     */
    public function getTagcategoriesCollection()
    {
        if ($this->isEnabled()) {
            $gccollection = $this->_tagcategoriesFactory->create();
            $gccollection->addFieldToFilter('is_active', 1);
            return $gccollection;
        }
    }
}
