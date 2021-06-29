<?php
namespace Ecopure\Gift\Model\Address;
use Ecopure\Gift\Model\ResourceModel\Address\CollectionFactory;
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $addressCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $addressCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $addressCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = array();

        foreach ($items as $address) {
            $this->loadedData[$address->getId()]['address'] = $address->getData();
        }


        return $this->loadedData;

    }
}
