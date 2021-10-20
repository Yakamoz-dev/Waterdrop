<?php
namespace Ecopure\Catalog\Block;

class CouponInfo extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry = null;
    public $_storeManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->_storeManager = $context->getStoreManager();
        parent::__construct($context,$data);
    }

    public function getProduct()
    {
        if ($product = $this->_coreRegistry->registry('current_product')) {
            return $product;
        }else{
            return null;
        }
    }
}
