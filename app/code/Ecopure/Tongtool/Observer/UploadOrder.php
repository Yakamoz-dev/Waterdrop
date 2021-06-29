<?php

namespace Ecopure\Tongtool\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class UploadOrder implements ObserverInterface
{
    protected $_logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_logger = $logger;
    }

    public function execute(Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            $this->_logger->info(json_encode($order->getData()));
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }
}