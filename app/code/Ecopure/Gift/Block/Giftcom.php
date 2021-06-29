<?php
namespace Ecopure\Gift\Block;

class Giftcom extends \Magento\Framework\View\Element\Template
{
    public $_storeManager;
    public $_customerFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ecopure\Gift\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
        array $data = []
    )
    {
        $this->_storeManager = $context->getStoreManager();
        $this->_customerFactory = $customerFactory;
        parent::__construct($context,$data);
    }
}
