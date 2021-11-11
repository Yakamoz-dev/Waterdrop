<?php

namespace Ecopure\Tongtool\Cron;

class Get
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
        $this->_logger->info("------Start get tracking------");
        $tableName = $this->getTableName('tongtool');
        $query = 'SELECT * FROM ' . $tableName . ' WHERE is_cancelled = 0 AND is_completed = 0 AND is_uploaded = 1';
        $results = $this->resourceConnection->getConnection()->fetchAll($query);
        if (!$results) {
            return false;
        }
        try {
            $result = $this->getTracking($results);
            if ($result) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $trackingInfo = json_decode($result);
                $tracking = $trackingInfo->datas->array;
                foreach ($tracking as $key => $orderTrackingInfo) {
                    $trackingNumber = $orderTrackingInfo->trackingNumber;
                    $tongtoolOrderId = $orderTrackingInfo->orderId;

                    if (isset($trackingNumber) && !empty($trackingNumber)) {
                        $this->_logger->info("tracking order: ".$tongtoolOrderId);
                        $orderId = str_replace("M_", "", $orderTrackingInfo->orderId);
                        $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId);

                        $tracksCollection = $order->getTracksCollection();
                        foreach ($tracksCollection->getItems() as $track) {
                            $trackNumbers[] = $track->getTrackNumber();
                        }

                        if (empty($tracknums[0])) {
                            $convertOrder = $objectManager->create('Magento\Sales\Model\Convert\Order');
                            $shipment = $convertOrder->toShipment($order);
                            foreach ($order->getAllItems() as $orderItem) {
                                if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                                    continue;
                                }
                                $qtyShipped = $orderItem->getQtyToShip();
                                $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);
                                $shipment->addItem($shipmentItem);
                            }
                            $carrier = $this->getCarrier($trackingNumber);
                            $track_data = array(
                                'carrier_code' => $carrier['carrierCode'],
                                'title' => $carrier['carrierName'],
                                'number' => $trackingNumber
                            );
                            $shipment->register();
                            $shipment->getOrder()->setIsInProcess(true);

                            $track = $objectManager->create('Magento\Sales\Model\Order\Shipment\TrackFactory')->create()->addData($track_data);
                            $shipment->addTrack($track)->save();
                            $shipment->save();
                            $shipment->getOrder()->save();
                            // Send email
//                            $objectManager->create('Magento\Shipping\Model\ShipmentNotifier')->notify($shipment);
//                            $shipment->save();

                            $tongtoolOrder = $objectManager->create('Ecopure\Tongtool\Model\Tongtool')->load($tongtoolOrderId, 'order_id');
                            $tongtoolOrder->setTrackingNo($trackingNumber);
                            $tongtoolOrder->setIsCompleted(1);
                            $tongtoolOrder->save();
                        } else {
                            $tongtoolOrder = $objectManager->create('Ecopure\Tongtool\Model\Tongtool')->load($tongtoolOrderId, 'order_id');
                            $tongtoolOrder->setIsCompleted(1);
                            $tongtoolOrder->save();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }

        $this->_logger->info("------End get tracking------");
        return $this;
    }

    public function getTracking($list) {
        if (!$list) {
            return false;
        }
        $action = 'trackingNumberQuery';
        $finalUrl = $this->getPostUrl($action);
        $roderData = array();
        foreach ($list as $key => $order) {
            $roderData[$key] = $order['order_id'];
        }
        $data = array(
            'merchantId' => '2a66c9aa291efb5c816f7b77eeef821f',
            'orderIds' => $roderData,
            'pageNo' => '1',
            'pageSize' => '100'
        );
        $fields_string = json_encode($data);

        $retry = 3;
        $ch = curl_init($finalUrl);
        // Set the CURL options
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($fields_string)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        $result = curl_exec($ch);

        while((strpos($result, '200') === FALSE) && $retry--){ //检查$targe是否存在
            sleep(1); //阻塞1s
            $result = curl_exec($ch);
        }
        curl_close($ch);
        return $result;
    }

    public function getPostUrl($action)
    {
        $baseUrl = "https://open.tongtool.com/api-service/openapi/tongtool/";
        $appToken = 'a7e441f3603de3b060551b198ff170c4';
        $timestamp = time();
        $secretKey = '8bc1d64ab6c6472d83495bf8bba1ed53776e726baa954f70ad051b1b1996a877';
        $sign = "app_token" . $appToken . "timestamp" . $timestamp . $secretKey;
        $md5 = md5($sign);
        $params = $action .'?app_token=' . $appToken . '&timestamp=' . $timestamp . '&sign=' . $md5;
        $finalUrl = $baseUrl . $params;
        $str = stripslashes($finalUrl);
        return $str;
    }

    public function getCarrier($trackingNumber)
    {
        if (substr($trackingNumber, 0, 1) === "9") {
            $carrierCode = "usps";
            $carrierName = "United States Postal Service";
        } elseif (substr($trackingNumber, 0, 2) === "1Z") {
            $carrierCode = "ups";
            $carrierName = "United Parcel Service";
        } elseif (substr($trackingNumber, 0, 3) === "TBA") {
            $carrierCode = "simpleshipping";
            $carrierName = "Amazon Logistics";
        }
        else {
            $carrierCode = "fedex";
            $carrierName = "Federal Express";
        }
        $carrier = [
            'carrierCode' => $carrierCode,
            'carrierName' => $carrierName,
        ];
        return $carrier;
    }
}
