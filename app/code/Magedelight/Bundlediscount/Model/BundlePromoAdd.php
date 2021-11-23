<?php

/**
 * Magedelight
 * Copyright (C) 2019 Magedelight <info@magedelight.com>
 *
 * @category Magedelight
 * @package Magedelight_Bundlediscount
 * @copyright Copyright (c) 2019 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

namespace Magedelight\Bundlediscount\Model;

use Magedelight\Bundlediscount\Api\BundlePromoAddInterface;
use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Form\FormKey;
/**
 * @api
 */

class BundlePromoAdd implements BundlePromoAddInterface
{
    /**
     * @var QuoteIdMaskFactory
     */
    protected $quoteIdMaskFactory;

    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var \Magento\Quote\Api\CartItemRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\CollectionFactory
     */
    protected $mdBundleDiscountCollObj;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory
     */
    protected $mdBundleItemsCollObj;

    /**
     * @var BundlediscountFactory
     */
    protected $bundlediscountFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    protected $connection;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    protected $request;
    protected $quote;
    protected $product;
    protected $customerFactory;
    protected $customerSession;
    protected $quoteRepository;
    /**
     * BundlePromoAdd constructor.
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param CartItemRepositoryInterface $repository
     * @param ResourceModel\Bundlediscount\CollectionFactory $mdBundleDiscountCollObj
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param ResourceModel\Bundleitems\CollectionFactory $mdBundleItemsCollObj
     */
    public function __construct(
         QuoteIdMaskFactory $quoteIdMaskFactory,
         \Magento\Quote\Api\CartItemRepositoryInterface $repository,
         \Magedelight\Bundlediscount\Model\ResourceModel\Bundlediscount\CollectionFactory $mdBundleDiscountCollObj,
         \Magento\Catalog\Model\ProductFactory $productFactory,
         \Magento\Catalog\Model\Product $product,
         \Magedelight\Bundlediscount\Model\ResourceModel\Bundleitems\CollectionFactory $mdBundleItemsCollObj,
         \Magedelight\Bundlediscount\Model\BundlediscountFactory $bundlediscountFactory,
         ProductRepositoryInterface $productRepository,
         CustomerCart $cart,
         \Magento\Store\Model\StoreManagerInterface $storeManager,
         RequestInterface $request,
         FormKey $formKey,
         Quote $quote,
         \Magento\Customer\Model\CustomerFactory $customerFactory,
         \Magento\Customer\Model\SessionFactory $customerSession,
         \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
         \Magento\Framework\App\ResourceConnection $connection
    ){
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->repository = $repository;
        $this->mdBundleDiscountCollObj = $mdBundleDiscountCollObj;
        $this->productFactory = $productFactory;
        $this->mdBundleItemsCollObj = $mdBundleItemsCollObj;
        $this->bundleDiscount = $bundlediscountFactory;
        $this->productRepository = $productRepository;
        $this->cart = $cart;
        $this->_storeManager = $storeManager;
        $this->request = $request;
        $this->formKey = $formKey;
        $this->quote = $quote;
        $this->product = $product;
        $this->customerFactory = $customerFactory;
        $this->customerSession = $customerSession;
        $this->quoteRepository = $quoteRepository;
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function getBundleByCart($cartId)
    {
        $quote = $this->quoteRepository->getActive($cartId);
        
        $bundleIds = explode(',', $quote->getBundleIds()); 
        //echo $quote->getBundleIds(); exit;
        $connection  = $this->connection->getConnection();
        $tableName   = $connection->getTableName('md_bundlediscount_bundles');
        $tableName2   = $connection->getTableName('md_bundlediscount_items');
        $results = [];
        $productids = [];
        $productqty = [];
        foreach ($quote->getAllVisibleItems() as $item) {
            $query = "SELECT * FROM ".$tableName." WHERE product_id = ".$item->getProductId()." ";
            $results[] = $connection->fetchAll($query);
        }
        
        $product = $this->productFactory->create();
        $pdata = [];
        $p = [];
        foreach ($results as $res) {
            $p = [];
            $pdata = [];
            foreach ($res as $r) {
                $productObj = $product->load($r['product_id']);
                $queryp = "SELECT * FROM ".$tableName2." WHERE bundle_id = ". $r['bundle_id']." ";
                $resultsp = $connection->fetchAll($queryp);
                $pdata[] = array(
                    'product_id' => $r['product_id'],
                    'name' => $productObj->getName(),
                    'qty' => $r['qty'],
                    'sort_order' => $r['sort_order']
                );
                $pdata1 = [];
                foreach ($resultsp as $resp) 
                {
                    $productObjs = $product->load($resp['product_id']);
                    $pdata1[] = array(
                        'product_id' => $resp['product_id'],
                        'name' => $productObjs->getName(),
                        'qty' => $resp['qty'],
                        'sort_order' => $resp['sort_order']
                    );

                }
                $p = array_merge($pdata,$pdata1);
                $productdata[$r['bundle_id']]['items'][] = array(
                    'bundle_id' => $r['bundle_id'],
                    'name' => $r['name'],
                    'discount' => $r['discount_price'],
                    'status' => $r['status'],
                    'product_items' => $p
                    
                );
            }
        }
        return $productdata;
       
    }

    public function AddBundlePromoToCart($bundleId, $storeId, $customerId)
    {
        $customerSession =  $this->customerSession->create();
        
        $customerSession->loginById($customerId);
    
        try 
        {
            $customer= $this->customerFactory->create();

            $websiteId = $this->_storeManager->getStore()->getWebsiteId();
            $customer->setWebsiteId($websiteId);
            $customer->load($customerId);

            $this->_storeManager->setCurrentStore($storeId);
            $products = $this->_getProductsAndQtys($bundleId);

            foreach ($products as $productId => $qty) {
                $productParams['qty'] = 1;
                $product = $this->productRepository->getById($productId, false, $storeId);
                $params = ['qty' => $qty, 'super_attribute' => [], 'options' => []];
                $this->cart->getQuote()->setBundleIds($bundleId);
                $this->cart->addProduct($product, $params);

            }
            $this->cart->save();
            return "You have successfully added bundle product to the cart.";
        }catch(\Exception $e){
            throw new CouldNotSaveException(__('Could not add bundle promo'));
        }


    }

    /**
     * @param $bundleId
     * @return array
     */
    protected function _getProductsAndQtys($bundleId)
    {
        $qtyArray = [];
        $bundle = $this->bundleDiscount->create()->load($bundleId);
        $selections = $bundle->getSelections();
        $qtyArray[$bundle->getProductId()] = $bundle->getQty();
        foreach ($selections as $_selection) {
            $qtyArray[$_selection->getProductId()] = $_selection->getQty();
        }
        return $qtyArray;
    }
}