<?php
namespace Ecopure\Gift\Model\ResourceModel\Address;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
//    protected $_idFieldName = 'address_id';
//    protected $_eventPrefix = 'ecopure_gift_address_collection';
//    protected $_eventObject = 'address_collection';
    protected $storeManager;

    /**
     * Define resource model
     *
     * @return void
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->_init('Ecopure\Gift\Model\Address', 'Ecopure\Gift\Model\ResourceModel\Address');
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }

    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()->joinLeft(
            ['ro_order' => $this->getTable('ro_order')],
            'main_table.customer_id = ro_order.customer_id',
            ['options'=>'options']
        );
        $this->getSelect()->joinLeft(
            ['ro_customer' => $this->getTable('ro_customer')],
            'main_table.customer_id = ro_customer.id',
            ['ro_id']
        );
        $this->getSelect()->joinLeft(
            ['ro' => $this->getTable('ro')],
            'ro.ro_id = ro_customer.ro_id',
            ['ro_asin'=>'ro_asin','ro_model_no'=>'ro_model_no']
        );
        $this->addFilterToMap('options', 'ro_order.options');
        $this->addFilterToMap('ro_asin', 'ro.ro_asin');
        $this->addFilterToMap('ro_model_no', 'ro.ro_model_no');
        return $this;
    }
}
