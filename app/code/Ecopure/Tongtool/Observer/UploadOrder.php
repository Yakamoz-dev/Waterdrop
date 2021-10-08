<?php

namespace Ecopure\Tongtool\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class UploadOrder implements ObserverInterface
{
    protected $_logger;

    public function __construct(
        \Ecopure\Tongtool\Logger\Logger $logger
    ) {
        $this->_logger = $logger;
    }

    public function execute(Observer $observer)
    {
        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $order = $observer->getEvent()->getOrder();
            $orderItems = $order->getAllVisibleItems();
            $incrementId = $order->getIncrementId();
            $phone   = trim($order->getShippingAddress()->getData('telephone'));
            $firstname   = trim($order->getShippingAddress()->getData('firstname'));
            $lastname   = trim($order->getShippingAddress()->getData('lastname'));
            $street = $order->getShippingAddress()->getStreet();
            $address1 = isset($street[0]) ? trim($street[0]) : "";
            $address2 = isset($street[1]) ? trim($street[1]) : "";
            $shippingMethod = $order->getShippingDescription();
            $country = trim($order->getShippingAddress()->getData('country_id'));
            $zipCode = trim($order->getShippingAddress()->getData('postcode'));
            $stateId = trim($order->getShippingAddress()->getData('region_id'));
            $region = $objectManager->create('Magento\Directory\Model\Region')->load($stateId);
            $regionCode = $region->getCode();
            $email = trim($order->getShippingAddress()->getData('email'));
            $city = trim($order->getShippingAddress()->getData('city'));
            $record['shipping_method'] = $shippingMethod;
            $record['country'] = $country;
            $record['customer_name'] = $firstname . " " . $lastname;
            $record['order_id'] = 'M_' . $incrementId;
            $record['magento_id'] = $incrementId;
            $record['phone'] = $this->phoneNumberFormat($phone);
            $record['zip'] = $zipCode;
            $record['state'] = $regionCode;
            $record['email'] = $email;
            $record['city'] = $city;
            $record['address1'] = $address1;
            $record['address2'] = $address2;
            $record['time'] = date("Y-m-d H:i:s");
            $record['is_cancelled'] = 0;
            $record['is_completed'] = 0;
            $items = array();
            foreach ($orderItems as $key => $item) {
                if($item->getProductType() == "simple") {
                    $items[$key] = new \stdClass();
                    $items[$key]->sku = $item->getSku();
                    $items[$key]->qty = json_encode($item->getQtyOrdered());
                }
            }
            $record['order_items'] = json_encode(array_values($items));
            $result = $this->processOrder($record);

            $this->_logger->info("------Upload Order Result------");
            $this->_logger->info(json_encode($result));
            $this->_logger->info("------Upload Order Result------");

            if (strpos($result, 'Success') === FALSE) {
                $record['is_uploaded'] = 0;
                $record['error'] = json_decode($result)->message;
            } else {
                $record['is_uploaded'] = 1;
            }

            $newOrder = $objectManager->create('Ecopure\Tongtool\Model\Tongtool');
            $newOrder->setData($record);
            $newOrder->save();

            $this->_logger->info("------Save Order Result------");
            $this->_logger->info(json_encode($record));
            $this->_logger->info("------Save Order Result------");
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }

    public function phoneNumberFormat($number)
    {
        // Allow only Digits, remove all other characters.
        $number = preg_replace("/[^\d]/","",$number);
        // get number length.
        $length = strlen($number);
        // if number = 10
        if($length == 10) {
            $number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
        }
        return $number;
    }

    public function processOrder($record) {
        $action = "orderImport";
        $finalUrl = $this->getPostUrl($action);
        $product = json_decode($record['order_items']);
        // Specify the POST data
        foreach ($product as $key => $item) {
            $transactionData[$key] = [
                'shippingFeeIncome' => null,
                'shipType' => $record['shipping_method'],
                'shippingFeeIncomeCurrency' => null,
                'productsTotalPriceCurrency' => null,
                'productsTotalPrice' => null,
                'quantity' => $item->qty,
                'sku' => $item->sku,
                'orderTransactionNum' => $record['order_id'],
                'goodsDetailRemark' => null
            ];
            $paymentInfos[$key] = [
                'orderAmount' => '0.00',
                'orderAmountCurrency' => 'USD',
                'paymentAccount' => '',
                'paymentDate' => $record['time'],
                'paymentNotes' => '',
                'paymentTransactionNum' => '',
                'recipientAccount' => '',
                'url' => ''
            ];
        }

        $buyerInfo = [
            'buyerAccount' => $record['customer_name'],
            'buyerAddress1' => $record['address1'],
            'buyerAddress2' =>  $record['address2'],
            'buyerAddress3' =>  null,
            'buyerCity' => $record['city'],
            'buyerCountryCode' => $record['country'],
            'buyerEmail' =>  $record['email'],
            'buyerMobilePhone' => $record['phone'],
            'buyerName' => $record['customer_name'],
            'buyerPhone' => $record['phone'],
            'buyerPostalCode' => $record['zip'],
            'buyerState' => $record['state']
        ];

        $orderData = [
            'buyerInfo' => $buyerInfo,
            'currency' => 'USD',
            'insuranceIncome' => '0',
            'insuranceIncomeCurrency' => 'USD',
            'notes' => '',
            'ordercurrency' => 'USD',
            'paymentInfos' => $paymentInfos,
            'platformCode' => 'magentosync',
            'saleRecordNum' =>  $record['order_id'],
            'sellerAccountCode' => '17685581077',
            'taxIncome' => '',
            'taxIncomeCurrency' => '',
            'totalPrice' => '',
            'totalPriceCurrency' => '',
            'transactions' => $transactionData
        ];

        $allData = [
            'order' => $orderData,
            'merchantId' => '2a66c9aa291efb5c816f7b77eeef821f',
            'addOnExists' => 'N'
        ];

        $fields_string = json_encode($allData, JSON_UNESCAPED_SLASHES);

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
}
