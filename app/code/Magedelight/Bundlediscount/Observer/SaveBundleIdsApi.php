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
use Magento\Framework\Webapi\Rest\Request;

class SaveBundleIdsApi implements ObserverInterface
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return bool
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $params = $this->request->getBodyParams();
        $bundleId = [];
        if(isset($params['bundleId'])){
            $bundleId = $params['bundleId'];
        }

        $quoteItem = $observer->getEvent()->getQuoteItem();
        $quote = $quoteItem->getQuote();

        $bundleIds = explode(',', $quote->getData('bundle_ids'));
        if(!empty($bundleId))
        {
            if (!in_array($bundleId, $bundleIds)) {
                $bundleIds[] = $bundleId;
            }
        }        
        
        
        if ($bundleIds[0] == '') {
            unset($bundleIds[0]);
        }
        //echo "<pre>"; print_r($bundleIds); exit;
        if(isset($bundleIds[1]) && !empty($bundleIds[1]))
        {
            $quote->setData('bundle_ids', implode(',', $bundleIds));
        }
        $quote->save();
        return $this;
    }
}