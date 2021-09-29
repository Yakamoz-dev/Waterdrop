<?php

namespace Ecopure\Tongtool\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RefundOrder implements ObserverInterface
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

            $creditmemo = $observer->getEvent()->getCreditmemo();
            $order = $creditmemo->getOrder();

            $incrementId = $order->getIncrementId();
            $orderId = 'M_' . $incrementId;

            $tongtoolOrder = $objectManager->create('Ecopure\Tongtool\Model\Tongtool')->load($orderId, 'order_id');
            $tongtoolOrder->setIsCancelled(1);
            $tongtoolOrder->save();

            $this->_logger->info("Refund Order: ".$incrementId);

        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }
}
