<?php

namespace Ecopure\Gift\Model;

use Magento\Framework\Mail\Template\TransportBuilder;

class Email
{

    public function __construct(TransportBuilder $transportBuilder) {
        $this->transportBuilder = $transportBuilder;
    }

    public function execute($name,$email) {
//        $report = [
//            'report_date' => date("j F Y", strtotime('-1 day')),
//            'orders_count' => rand(1, 10),
//            'order_items_count' => rand(1, 10),
//            'avg_items' => rand(1, 10)
//        ];
        $report = [];

        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($report);

        $transport = $this->transportBuilder
            ->setTemplateIdentifier('warranty_reg_template')
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
            ->setTemplateVars(['data' => $postObject])
            ->setFrom(['name' => 'Water-Filter','email' => 'syz8734181@126.com'])
            ->addTo($email,$name)
            ->getTransport();
        $transport->sendMessage();
    }
}