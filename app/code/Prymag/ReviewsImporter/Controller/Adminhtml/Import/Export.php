<?php

namespace Prymag\ReviewsImporter\Controller\Adminhtml\Import;

use Magento\Framework\App\Filesystem\DirectoryList;
USE \Magento\Review\Model\Review;

class Export extends \Magento\Backend\App\Action {

    protected $_fileFactory;
    protected $directory;
    protected $_reviewFactory;
    protected $_storeManager;
    protected $objectManager;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager

    ) {
        parent::__construct($context);
        $this->_fileFactory = $fileFactory;
        $this->_reviewFactory = $reviewFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR); // VAR Directory Path
        $this->_storeManager = $storeManager;
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function execute()
    {
        $name = date('Y-m-d-H-i-s');
        $filepath = 'export/export-data-' .$name. '.csv'; // at Directory path Create a Folder Export and FIle
        $this->directory->create('export');

        $stream = $this->directory->openFile($filepath, 'w+');
        $stream->lock();

        //column name dispay in your CSV

        $columns = ['ID','PRODUCT','SKU','EMAIL','NICKNAME','RATING','TITLE','DETAIL','DATE','STATUS'];

        foreach ($columns as $column)
        {
            $header[] = $column; //storecolumn in Header array
        }

        $stream->writeCsv($header);

        $reviews = $this->_reviewFactory->create()
            ->setDateOrder()
            ->addRateVotes();
        $i = 1;
        foreach ($reviews as $review) {
            $product = $this->objectManager->create('Magento\Catalog\Model\Product')->load($review->getEntityPkValue());
            $sku = $product->getSku();
            $ratings = $review->getRatingVotes();
            $customer_rating = '';
            foreach ($ratings as $rating) {
                $customer_rating = $rating->getValue();
                break;
            }
            $email = '';
            if ($review->getCustomerId()) {
                $customer = $this->objectManager->create('Magento\Customer\Model\Customer')->load($review->getCustomerId());
                $email = $customer->getEmail();
            }
            $itemData = [];

            // column name must same as in your Database Table

            $itemData[] = $i;
            $itemData[] = $review->getEntityPkValue();
            $itemData[] = $sku;
            $itemData[] = $email;
            $itemData[] = $review->getNickname();
            $itemData[] = $customer_rating;
            $itemData[] = $review->getTitle();
            $itemData[] = $review->getDetail();
            $itemData[] = date('m/d/Y',strtotime($review->getCreatedAt()));
            $itemData[] = $review->getStatusId();

            $stream->writeCsv($itemData);
            $i++;
        }

        $content = [];
        $content['type'] = 'filename'; // must keep filename
        $content['value'] = $filepath;
        $content['rm'] = '1'; //remove csv from var folder

        $csvfilename = 'reviews-'.$name.'.csv';
        return $this->_fileFactory->create($csvfilename, $content, DirectoryList::VAR_DIR);

    }

}