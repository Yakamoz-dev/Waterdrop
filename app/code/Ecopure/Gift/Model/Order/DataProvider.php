<?php
namespace Ecopure\Gift\Model\Order;
use Ecopure\Gift\Model\ResourceModel\Order\CollectionFactory;
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $orderCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $orderCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $orderCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = array();

        foreach ($items as $order) {
            $this->loadedData[$order->getId()]['order'] = $order->getData();
        }


        return $this->loadedData;

    }
}
