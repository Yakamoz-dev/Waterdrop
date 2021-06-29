<?php

namespace Ecopure\TrackingNumber\Controller\Adminhtml\Import;

use Magento\Backend\App\Response\Http\FileFactory;
use Magento\Framework\Filesystem\DirectoryList;

class Index extends \Magento\Backend\App\Action {
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
         * @return \Magento\Framework\View\Result\Page
         */
        public function execute()
        {

            if( isset($this->getRequest()->getParams()['download_sample']) ){
                $heading = array(
                    'ORDER_NUMBER',
                    'CARRIER_CODE',
                    'TITLE',
                    'NUMBER'
                );
                
                $filename = 'trackingnumber_importer_sample.csv';
                $handle = fopen( $filename , 'w');
                fputcsv($handle, $heading);

                $data = $this->getSampleData();
                foreach($data as $d){
                    fputcsv($handle, $d);
                }

                $this->downloadCsv( $filename );
            }

            return  $resultPage = $this->resultPageFactory->create();
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
                    '000000001',
                    'ups',
                    'United Parcel Service',
                    'TORD23254WERZXd3'
                ),
                array(
                    '000000002',
                    'ups',
                    'United Parcel Service',
                    'TORD23254WERZXd2'
                ),
                array(
                    '000000003',
                    'ups',
                    'United Parcel Service',
                    'TORD23254WERZXd1'
                ),
            );
            return $data;
        }
}