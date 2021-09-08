<?php

namespace CjPublicis\UniversalTag\Test\Unit\Helper;

use CjPublicis\UniversalTag\Helper\Data;
use CjPublicis\UniversalTag\Model\CjOrder;

class DataTest extends \PHPUnit\Framework\TestCase
{
    protected $dataHelper;

    public function setUp()
    {

        $this->cjEvent = $this->getMockBuilder('CjPublicis\UniversalTag\Cookie\CjEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $this->order = $this->getMockBuilder('Magento\Sales\Model\Order')
            ->disableOriginalConstructor()
            ->getMock();

        $this->scopeConfigInterface = $this->getMockBuilder('Magento\Framework\App\Config\ScopeConfigInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->http = $this->getMockBuilder('\Magento\Framework\App\Request\Http')
            ->disableOriginalConstructor()
            ->getMock();

        $this->files = $this->getMockBuilder('\Magento\Framework\App\Utility\Files')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resourceConnection = $this->getMockBuilder('\Magento\Framework\App\ResourceConnection')
            ->disableOriginalConstructor()
            ->getMock();

        $this->dataHelper = new Data(
            $this->cjEvent,
            $this->order,
            $this->scopeConfigInterface,
            $this->http,
            $this->files,
            $this->resourceConnection
        );
    }

    public function testGetDefaultConfig()
    {
        $this->scopeConfigInterface->expects($this->exactly(3))
            ->method('getValue')
            ->withConsecutive(['vendor/module/enterprise_id'], ['vendor/module/action_id'], ['vendor/module/tag_id'])
            ->willReturnOnConsecutiveCalls($this->returnValue('123456'), $this->returnValue('105'), $this->returnValue('3'));

        $enterpriseIdValue = $this->dataHelper->getDefaultConfig('vendor/module/enterprise_id');
        $actionIdValue = $this->dataHelper->getDefaultConfig('vendor/module/action_id');
        $tagIdValue = $this->dataHelper->getDefaultConfig('vendor/module/tag_id');
        $this->assertEquals($enterpriseIdValue, '123456');
        $this->assertEquals($actionIdValue, '105');
        $this->assertEquals($tagIdValue, '3');
    }


    public function testGetParams()
    {

        $this->http->expects($this->once())
            ->method('getParams')
            ->will($this->returnValue(array("cjevent" => "1234")));
        $params = $this->dataHelper->getParams();
        $this->assertEquals($params, array("cjevent" => "1234"));
    }


    private function setUpOrderCollection($orderId)
    {
        $orderCollection = $this->createMock(\Magento\Reports\Model\ResourceModel\Order\Collection::class);

        $this->order->expects($this->any())->method('getCollection')->will($this->returnValue($orderCollection));

        $orderCollection
            ->expects($this->any())
            ->method('addFieldToFilter')
            ->will($this->returnValue($orderCollection));
        $orderCollection
            ->expects($this->any())
            ->method('setOrder')
            ->will($this->returnValue($orderCollection));
        $orderCollection
            ->expects($this->any())
            ->method('getFirstItem')
            ->will($this->returnValue($this->order));
        $this->order
            ->expects($this->any())
            ->method('getIncrementId')
            ->will($this->returnValue($orderId));
    }

    public function testNewCustomer()
    {
        $orderId = "101";
        $this->setUpOrderCollection($orderId);
        $dataHelperMock = $this->createMock(Data::class);
        $dataHelperMock->expects($this->any())->method('getCustomerStatus')->will($this->returnValue("New"));

        $customerStatus = $dataHelperMock->getCustomerStatus("1", $orderId, 'abc@xyz.com');

        $this->assertEquals("New", $customerStatus);
    }

    public function testOldCustomer()
    {
        $orderId = "101";
        $this->setUpOrderCollection($orderId);
        $dataHelperMock = $this->createMock(Data::class);
        $dataHelperMock->expects($this->any())->method('getCustomerStatus')->will($this->returnValue("Return"));
        $customerStatus = $dataHelperMock->getCustomerStatus("1", $orderId, 'abc@xyz.com');

        $this->assertEquals("Return", $customerStatus);
    }

    public function testRoundTo2()
    {
        $amt1 = "10.0000";
        $amt2 = "10";
        $amt3 = "10.125";
        $amt4 = "-10.0000";
        $amt5 = "-10";
        $amt6 = "-10.125";
        $this->assertEquals("10.00", $this->dataHelper->roundTo2($amt1));
        $this->assertEquals("10.00", $this->dataHelper->roundTo2($amt2));
        $this->assertEquals("10.13", $this->dataHelper->roundTo2($amt3));
        $this->assertEquals("-10.00", $this->dataHelper->roundTo2($amt4));
        $this->assertEquals("-10.00", $this->dataHelper->roundTo2($amt5));
        $this->assertEquals("-10.13", $this->dataHelper->roundTo2($amt6));
    }

    public function testConvertOrderToCjOrder()
    {
        $orderId = "101";
        $cjOrderMock = $this->createMock(CjOrder::class);
        $cjOrderMock->expects($this->any())->Method('getCurrencyCode')->will($this->returnValue('USD'));
        $cjOrderMock->expects($this->any())->method('getSubTotal')->will($this->returnValue('261.0000'));
        $cjOrderMock->expects($this->any())->method('getCouponCode')->will($this->returnValue('H20'));
        $cjOrderMock->expects($this->any())->method('getCustomerId')->will($this->returnValue('1'));
        $cjOrderMock->expects($this->any())->method('getTaxAmount')->will($this->returnValue('16.8100'));
        $dataHelperMock = $this->createMock(Data::class);
        $dataHelperMock->expects($this->any())->method('convertOrderToCjOrder')->will($this->returnValue($cjOrderMock));
        $cjOrder = $dataHelperMock->convertOrderToCjOrder($orderId);
        $this->assertEquals("USD", $cjOrder->getCurrencyCode());
        $this->assertEquals("H20", $cjOrder->getCouponCode());
        $this->assertEquals("1", $cjOrder->getCustomerId());
        $this->assertEquals("16.81", $cjOrder->getTaxAmount());
    }
}


