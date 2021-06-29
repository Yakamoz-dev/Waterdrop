<?php
namespace Ecopure\Gift\Block;

class Check extends \Magento\Framework\View\Element\Template
{
    public $_storeManager;
    public $_productFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ecopure\Gift\Model\ResourceModel\Product\CollectionFactory $productFactory,
        array $data = []
    )
    {
        $this->_storeManager = $context->getStoreManager();
        $this->_productFactory = $productFactory;
        parent::__construct($context,$data);
    }
}
