<?php
namespace Ecopure\Gift\Block;

class Giftsu extends \Magento\Framework\View\Element\Template
{
    public $_storeManager;
    public $_customerFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    )
    {
        $this->_storeManager = $context->getStoreManager();
        parent::__construct($context,$data);
    }
}
