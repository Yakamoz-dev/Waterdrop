<?php
namespace Ecopure\Gift\Model\Gift;
use Ecopure\Gift\Model\ResourceModel\Gift\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $giftCollectionFactory
     * @param array $meta
     * @param array $data
     */
    protected $collection;
    protected $dataPersistor;
    public    $_storeManager;
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $giftCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $giftCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->_storeManager=$storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
//        if (isset($this->loadedData)) {
//            return $this->loadedData;
//        }
//
//        $items = $this->collection->getItems();
//        $this->loadedData = array();
//
//        foreach ($items as $gift) {
//            $this->loadedData[$gift->getId()]['gift'] = $gift->getData();
//        }
//
//
//        return $this->loadedData;

        $baseurl =  $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $gift) {
            $temp = $gift->getData();
            if($temp['image']):
                $img = [];
                $img[0]['image'] = $temp['image'];
                $img[0]['url'] = $baseurl.'gift/image/'.$temp['image'];
                $temp['image'] = $img;
            endif;
            if($temp['options']):
                $arr = json_decode($temp['options'],true);
                $temp['dynamic_rows_container']['dynamic_rows_container'] = $arr;
            endif;


            $data = $this->dataPersistor->get('gift');
            if (!empty($data)) {
                $gift = $this->collection->getNewEmptyItem();
                $gift->setData($data);
                $this->loadedData[$gift->getId()]['gift'] = $gift->getData();
                $this->dataPersistor->clear('gift');
            }else {
                if($items):
                    $t2[$gift->getId()]['gift'] = $temp;
                    return $t2;
                endif;
            }

            return $this->loadedData;
        }

    }
}
