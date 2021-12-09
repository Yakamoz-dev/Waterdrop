<?php

namespace Magedelight\Bundlediscount\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\UrlInterface;

class TopMenu implements ObserverInterface
{
    /**
     * TopMenu constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magedelight\Bundlediscount\Helper\Data $helper
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magedelight\Bundlediscount\Helper\Data $helper
    ) {
        $this->_storeManager = $storeManager;
        $this->helper = $helper;
    }

    /**
     * @param $observer
     *
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        $isEnable = $this->helper->isEnableFrontend();
        $isCatEnable =  $this->helper->isEnableBundleOn() == 'topnavagation' ? true : false;
        if ($isEnable && $isCatEnable) {
            $urlKey = trim($this->helper->getUrlKey(), '/');
            $suffix = trim($this->helper->getUrlSuffix(), '/');
            $urlKey .= (strlen($suffix) > 0 || $suffix != '') ? '.'.str_replace('.', '', $suffix) : '/';

            $url = $this->_storeManager->getStore()->getBaseUrl().$urlKey;
            $title = $this->helper->getLinkTitle();
            $menu = $observer->getMenu();
            $tree = $menu->getTree();
            $bundleNodeId = 'Bundle Discount';
            $data = [
                'name' => __($title),
                'id' => $bundleNodeId,
                'url' => $url,
            ];
            $bundleNode = new Node($data, 'id', $tree, $menu);
            $menu->addChild($bundleNode);
        }
        return $this;
    }
}
