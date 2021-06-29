<?php
namespace Ecopure\Gift\Block;

class Giftsel extends \Magento\Framework\View\Element\Template
{
    public $_storeManager;
    public $_giftFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ecopure\Gift\Model\ResourceModel\Gift\CollectionFactory $giftFactory,
        array $data = []
    )
    {
        $this->_storeManager = $context->getStoreManager();
        $this->_giftFactory = $giftFactory;
        parent::__construct($context,$data);
    }
}
