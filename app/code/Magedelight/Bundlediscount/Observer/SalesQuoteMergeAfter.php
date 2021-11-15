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

namespace Magedelight\Bundlediscount\Observer;

use Magento\Framework\Event\ObserverInterface;

class SalesQuoteMergeAfter implements ObserverInterface
{

     /**
      * AddDiscount constructor.
      * @param \Magedelight\Bundlediscount\Helper\Data $helper
      */
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
            $this->messageManager = $messageManager;
    }
    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
            $guestQuote = $observer->getEvent()->getSource();   //before merge
            $customerQuote = $observer->getEvent()->getQuote(); //after merge
        if (isset($guestQuote)) {
            if ($guestQuote->getBundleIds()) {
                $customerQuote->setBundleIds($guestQuote->getBundleIds());
                $customerQuote->setBundleDiscountAmount($guestQuote->getBundleDiscountAmount());
                $customerQuote->setBaseBundleDiscountAmount($guestQuote->getBaseBundleDiscountAmount());
            }
        }
            return $this;
    }
}
