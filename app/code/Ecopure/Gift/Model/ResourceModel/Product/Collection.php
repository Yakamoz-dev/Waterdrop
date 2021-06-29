<?php
namespace Ecopure\Gift\Model\ResourceModel\Product;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'ro_id';
    protected $_eventPrefix = 'ecopure_gift_product_collection';
    protected $_eventObject = 'product_collection';
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
        $this->storeManager = $storeManager;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    protected function _construct()
    {
        $this->_init('Ecopure\Gift\Model\Product', 'Ecopure\Gift\Model\ResourceModel\Product');
        $this->_map['fields']['ro_id'] = 'main_table.ro_id';
    }
}
