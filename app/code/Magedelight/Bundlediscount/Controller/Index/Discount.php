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

namespace Magedelight\Bundlediscount\Controller\Index;

class Discount extends \Magento\Framework\App\Action\Action
{
    /**
     * Discount constructor.
     * @param \Magedelight\Bundlediscount\Helper\Data $helper
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magedelight\Bundlediscount\Helper\Data $helper,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $layout = $this->helper->getPageLayout();
        if ($layout != 'empty') {
            $resultPage->getConfig()->addBodyClass('page-products');
        }
        if ($layout == '1column' || $layout == '3columns') {
            $resultPage->getConfig()->addBodyClass('page-with-filter');
        }
        $resultPage->getConfig()->setPageLayout($layout);
        return $resultPage;
    }
}
