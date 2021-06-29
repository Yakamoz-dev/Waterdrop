<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecopure\Review\Plugin;

class CustomReviewRender {

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->request = $request;
    }

    public function beforeSetTemplate(
        \Magento\Review\Block\Product\ReviewRenderer $subject,
        $result
    ) {
        if ($this->request->getFullActionName() == 'catalog_product_view') {
            //return 'Vendor_Module::custom_summary.phtml'; // For product view page => In core Magento_Review::helper/summary.phtml display
            return $result; // For product listing page => In core Magento_Review::helper/summary.phtml display
        }
        if ($this->request->getFullActionName() == 'catalog_category_view' || $this->request->getFullActionName() == 'catalogsearch_result_index') {
            return 'Ecopure_Review::helper/summary_short.phtml';
        }
        return $result;
    }

}
