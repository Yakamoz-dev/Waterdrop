<?php
namespace Ecopure\Tongtool\Model\ResourceModel\Order;

class Tongtool extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('tongtool', 'id');
    }
}
