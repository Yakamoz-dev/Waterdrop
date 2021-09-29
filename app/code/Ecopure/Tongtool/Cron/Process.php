<?php

namespace Ecopure\Tongtool\Cron;

class Process
{
    protected $resourceConnection;
    protected $_logger;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Ecopure\Tongtool\Logger\Logger $logger
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
        $this->_logger->info("------Start process------");
        $tableName = $this->getTableName('tongtool');
        $query = 'SELECT * FROM ' . $tableName . ' WHERE is_cancelled = 0 AND is_completed = 0 AND is_uploaded = 1';
        $results = $this->resourceConnection->getConnection()->fetchAll($query);
        if (!$results) {
            return false;
        }
        try {
            $this->syncTracking($results);
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }

        $this->_logger->info("------End process------");
        return $this;
    }

    public function syncTracking($list) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        foreach ($list as $key => $order) {
            $tongtoolId = $order['id'];
            $orderId = $order['magento_id'];
            $orderInfo = $objectManager->create('Magento\Sales\Model\Order');
            $order = $orderInfo->loadByIncrementId($orderId);
            $status = $order->getStatus();
            $tongtool = $objectManager->create('Ecopure\Tongtool\Model\Tongtool');
            $tongtoolUp = $tongtool->load($tongtoolId);
            $this->_logger->info($status);

            if(($status == 'closed') || ($status == 'canceled')){
                $tongtoolUp->setIsCancelled(1);
                $tongtoolUp->save();
            } elseif($status == 'complete') {
                $tongtoolUp->setIsCompleted(1);
                $tongtoolUp->save();
            } else {
                $trackNumbers = array();
                $tracksCollection = $order->getTracksCollection();
                $this->_logger->info(json_encode($tracksCollection));

                foreach ($tracksCollection->getItems() as $track) {
                    $trackNumbers[] = $track->getTrackNumber();
                }
                if(!empty($trackNumbers[0])){
                    try{
                        $trackingNumber = $trackNumbers[0];
                        $tongtoolUp->setTrackingNo($trackingNumber);
                        $tongtoolUp->setIsCompleted(1);
                        $tongtoolUp->save();
                    }catch (\Exception $e) {
                        $this->_logger->info($e->getMessage());
                    }
                }
            }
        }
    }
}
