<?php

namespace Ecopure\Tongtool\Cron;

class Process
{
    protected $resourceConnection;
    protected $_logger;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->_logger = $logger;
    }

    /**
     * Get Table name using direct query
     */
    public function getTablename($tableName)
    {
        /* Create Connection */
        $connection  = $this->resourceConnection->getConnection();
        $tableName   = $connection->getTableName($tableName);
        return $tableName;
    }

    public function execute()
    {

        $tableName = $this->getTableName('customer_entity');
        $query = 'SELECT * FROM ' . $tableName . ' LIMIT 2';
        /**
         * Execute the query and store the results in $results variable
         */
        $results = $this->resourceConnection->getConnection()->fetchAll($query);
        $this->_logger->info(json_encode($results));

        return $this;
    }
}