<?php
namespace Harrigo\EverCrumbs\Block;

use Magento\Catalog\Helper\Data;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\Store;
use Magento\Framework\Registry;

class Evercrumbs extends \Magento\Framework\View\Element\Template
{

    /**
     * Catalog data
     *
     * @var Data
     */
    protected $_catalogData = null;

    /**
     * @param Context $context
     * @param Data $catalogData
     * @param array $data
     */
    public function __construct(
		Context $context,
		Data $catalogData,
		Registry $registry,
		array $data = [])
    {
        $this->_catalogData = $catalogData;
		$this->registry = $registry;
        parent::__construct($context, $data);
    }

	public function getCrumbs()
    {
		$evercrumbs = array();

		$evercrumbs[] = array(
			'label' => 'Home',
			'title' => 'Go to Home Page',
			'link' => $this->_storeManager->getStore()->getBaseUrl()
		);

		$path = $this->_catalogData->getBreadcrumbPath();
		$product = $this->registry->registry('current_product');
		$categoryCollection = clone $product->getCategoryCollection();
		$categoryCollection->clear();
		$categoryCollection
            ->addAttributeToSort('level', $categoryCollection::SORT_ORDER_DESC)
            ->addAttributeToFilter('path', array('like' => "1/" . $this->_storeManager->getStore()->getRootCategoryId() . "/%"))
            ->addAttributeToFilter('entity_id', array('nin' => array(10,11,12,13,14,89,90,91,92,93,94,40,41,42,44,46,51,53,56,58,59,63,65,66,69,71,64,67,68,70,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,106)));
        $categoryCollection->setPageSize(1);
		$breadcrumbCategories = $categoryCollection->getFirstItem()->getParentCategories();
		foreach ($breadcrumbCategories as $category) {
			$evercrumbs[] = array(
				'label' => $category->getName(),
				'title' => $category->getName(),
				'link' => $category->getUrl()
			);
		}


		$evercrumbs[] = array(
				'label' => $product->getName(),
				'title' => $product->getName(),
				'link' => ''
			);

		return $evercrumbs;
    }
}
