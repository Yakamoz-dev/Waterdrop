<?php
namespace Ecopure\Catalog\Block;

class Information extends \Magento\Framework\View\Element\Template
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

    public function getCategory()
    {
        if ($category = $this->_coreRegistry->registry('current_category')) {
            return $category;
        }else{
            return null;
        }
    }
}
