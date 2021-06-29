<?php

namespace Ecopure\Gift\Controller\Adminhtml\Dataimport;

use Magento\Backend\App\Response\Http\FileFactory;
use Magento\Framework\Filesystem\DirectoryList;

class Download extends \Magento\Backend\App\Action {
    /**
        * @var \Magento\Framework\View\Result\PageFactory
        */
        protected $resultPageFactory;

        protected $downloader;

        protected $directory;

        /**
         * Constructor
         *
         * @param \Magento\Backend\App\Action\Context $context
         * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
         */
        public function __construct(
            \Magento\Backend\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            FileFactory $fileFactory,
            DirectoryList $directory
        ) {
            parent::__construct($context);
            $this->resultPageFactory = $resultPageFactory;

            $this->downloader = $fileFactory;
            $this->directory = $directory;
        }

        /**
         * @return void
         */
        public function execute()
        {

            $heading = array(
                'ro_productid',
                'ro_model_no',
                'ro_asin',
                'ro_order_no',
                'use_order',
                'only_reg',
                'country'
            );

            $filename = 'gift_product_sample.csv';
            $handle = fopen( $filename , 'w');
            fputcsv($handle, $heading);

            $data = $this->getSampleData();
            foreach($data as $d){
                fputcsv($handle, $d);
            }

            $this->downloadCsv( $filename );

            return;
        }

        public function downloadCsv( $filename ){
            if (file_exists($filename)) {
                $filePath = $this->directory->getPath("pub") . DIRECTORY_SEPARATOR . $filename;

                return $this->downloader->create($filename, @file_get_contents($filePath));
            }
        }

        public function getSampleData(){
            $data = array(
                array(
                    'N1W5RWZ00001',
                    'WD-N1-W',
                    'B08BLLQZ25',
                    '',
                    0,
                    0,
                    'US'
                ),
            );
            return $data;
        }
}
