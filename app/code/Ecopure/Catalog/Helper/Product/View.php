<?php
namespace Ecopure\Catalog\Helper\Product;

use Magento\Catalog\Helper\Product\View as ProductView;
use Magento\Framework\View\Result\Page as ResultPage;

class View extends ProductView
{
    private function preparePageMetadataCustom(ResultPage $resultPage, $product){
        $pageConfig = $resultPage->getConfig();

        $metaTitle = $product->getMetaTitle();
        $pageConfig->setMetaTitle($metaTitle);
        $pageConfig->getTitle()->set($metaTitle ?: $product->getName());

        $keyword = $product->getMetaKeyword();
        $currentCategory = $this->_coreRegistry->registry('current_category');
        if ($keyword) {
            $pageConfig->setKeywords($keyword);
        } elseif ($currentCategory) {
            $pageConfig->setKeywords($product->getName());
        }

        $description = $product->getMetaDescription();
        if ($description) {
            $pageConfig->setDescription($description);
        } else {
            $pageConfig->setDescription(substr(strip_tags($product->getDescription()), 0, 255));
        }

        if ($this->_catalogProduct->canUseCanonicalTag()) {
            /* Custom Rewrite */
            $custom_canonical_url = $product->getCanonicalUrl();
            if ($custom_canonical_url) {
                $pageConfig->addRemotePageAsset(
                    $custom_canonical_url,
                    'canonical',
                    ['attributes' => ['rel' => 'canonical']]
                );
            }
        }

        $pageMainTitle = $resultPage->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($product->getName());
        }

        return $this;
    }


    public function prepareAndRender(ResultPage $resultPage, $productId, $controller, $params = null)
    {
        /**
         * Remove default action handle from layout update to avoid its usage during processing of another action,
         * It is possible that forwarding to another action occurs, e.g. to 'noroute'.
         * Default action handle is restored just before the end of current method.
         */
        $defaultActionHandle = $resultPage->getDefaultLayoutHandle();
        $handles = $resultPage->getLayout()->getUpdate()->getHandles();
        if (in_array($defaultActionHandle, $handles)) {
            $resultPage->getLayout()->getUpdate()->removeHandle($resultPage->getDefaultLayoutHandle());
        }

        if (!$controller instanceof \Magento\Catalog\Controller\Product\View\ViewInterface) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Bad controller interface for showing product')
            );
        }
        // Prepare data
        $productHelper = $this->_catalogProduct;
        if (!$params) {
            $params = new \Magento\Framework\DataObject();
        }

        // Standard algorithm to prepare and render product view page
        $product = $productHelper->initProduct($productId, $controller, $params);
        if (!$product) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Product is not loaded'));
        }

        $buyRequest = $params->getBuyRequest();
        if ($buyRequest) {
            $productHelper->prepareProductOptions($product, $buyRequest);
        }

        if ($params->hasConfigureMode()) {
            $product->setConfigureMode($params->getConfigureMode());
        }

        $this->_eventManager->dispatch('catalog_controller_product_view', ['product' => $product]);

        $this->_catalogSession->setLastViewedProductId($product->getId());

        if (in_array($defaultActionHandle, $handles)) {
            $resultPage->addDefaultHandle();
        }

        $this->initProductLayout($resultPage, $product, $params);
        /* Custom Rewrite */
        //$this->preparePageMetadata($resultPage, $product);
        $this->preparePageMetadataCustom($resultPage, $product);
        /* Custom Rewrite */
        return $this;
    }
}
