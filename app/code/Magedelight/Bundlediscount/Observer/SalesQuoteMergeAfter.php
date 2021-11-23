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
    protected $customerSession;
    protected $bundleDiscountFactory;
     /**
      * AddDiscount constructor.
      * @param \Magedelight\Bundlediscount\Helper\Data $helper
      */
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\CollectionFactory $bundleDiscountFactory
    ) {
            $this->messageManager = $messageManager;
            $this->customerSession = $customerSession;
            $this->bundleDiscountFactory = $bundleDiscountFactory;
    }
    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerGroupId = $this->customerSession->getCustomerGroupId();

        $guestQuote = $observer->getEvent()->getSource();   //before merge
        $customerQuote = $observer->getEvent()->getQuote(); //after merge
        if (isset($guestQuote)) {
            if ($guestQuote->getBundleIds()) {

                $bundleDiscountData = $this->bundleDiscountFactory->create()
                  ->addFieldToSelect('customer_groups')
                  ->addFieldToFilter('bundle_id', $guestQuote->getBundleIds())
                  ->getFirstItem();

                $customerbundleIds = $customerQuote->getBundleIds();

                $custGrp = explode(',', $bundleDiscountData->getData('customer_groups'));

                if (in_array($customerGroupId, $custGrp)) {
                    $guestBundleIds = $guestQuote->getBundleIds();
                    $bundleIds = $customerbundleIds .','. $guestBundleIds;

                    $customerQuote->setBundleIds($bundleIds);
                    $customerQuote->setBundleDiscountAmount($guestQuote->getBundleDiscountAmount());
                    $customerQuote->setBaseBundleDiscountAmount($guestQuote->getBaseBundleDiscountAmount());
                }

            }
        }
            return $this;
    }
}
