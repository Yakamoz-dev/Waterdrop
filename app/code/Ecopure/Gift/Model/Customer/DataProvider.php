<?php
namespace Ecopure\Gift\Model\Customer;
use Ecopure\Gift\Model\ResourceModel\Customer\CollectionFactory;
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $customerCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $customerCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $customerCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = array();

        foreach ($items as $customer) {
            $this->loadedData[$customer->getId()]['customer'] = $customer->getData();
        }


        return $this->loadedData;

    }
}
