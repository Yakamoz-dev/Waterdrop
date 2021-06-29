<?php
namespace Ecopure\Gift\Block;

class Gift extends \Magento\Framework\View\Element\Template
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

    public function getModelNos() {
        $collection = $this->_productFactory->create();
        $collection->getSelect()->group('ro_model_no')->order('ro_model_no');
        $datas = $collection->getData();
        $modelNos = [];
        for ($i = 0; $i < count($datas); $i++) {
            $modelNos[$i] = ['ro_model_no' => $datas[$i]['ro_model_no'],'use_order' => $datas[$i]['use_order']];
        }
        return $modelNos;
    }
}
