<?php

namespace Ecopure\TrackingNumber\Controller\Adminhtml\Import;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action {
    /**
    * @var \Magento\Framework\View\Result\PageFactory
    */
    protected $resultPageFactory;

    protected $uploaderFactory;

    protected $varDirectory;

    protected $csvProcessor;

    protected $_objectManager;

    /**
     * @var array
     */
    protected $ratings;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csvProcessor
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->varDirectory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR); // Get default 'var' directory
        $this->csvProcessor = $csvProcessor;

        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }

    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('trackingnumber_importer/import/index');

        try{
            /**
             * fileId field must be the same name as the upload_field name from the form block
             * see Prymag\ReviewsImporter\Block\Adminhtml\Edit\Form
             */
            $uploader = $this->uploaderFactory->create(['fileId' => 'trackingnumber_import_file']);
            $uploader->checkAllowedExtension('csv');
            $uploader->skipDbProcessing(true);
            $result = $uploader->save($this->getWorkingDir());

            $this->validateIfHasExtension($result);
        }
        catch( \Exception $e) {
            $this->messageManager->addError( __( $e->getMessage() ) );
            return $resultRedirect;
        }

        $this->processUpload($result);

        $this->messageManager->addSuccess( __( 'Tracking Number imported' ) );

        return $resultRedirect;

        //return  $resultPage = $this->resultPageFactory->create();
    }

    public function validateIfHasExtension($result){
        $extension = pathinfo($result['file'], PATHINFO_EXTENSION);

        $uploadedFile = $result['path'] . $result['file'];
        if (!$extension) {
            $this->varDirectory->delete($uploadedFile);
            throw new \Exception(__('The file you uploaded has no extension.'));
        }
    }

    public function getWorkingDir(){
        return $this->varDirectory->getAbsolutePath('importexport/');
    }

    /**
     * Process uploaded csv file
     *
     * @param [type] $result
     * @return void
     */
    public function processUpload( $result ){

        $sourceFile = $this->getWorkingDir() . $result['file'];

        $rows = $this->csvProcessor->getData($sourceFile);
        $header = array_shift($rows);

        // See \Magento\ReviewSampleData\Model\Review::install()
        foreach ($rows as $row) {
            $data = [];
            foreach ($row as $key => $value) {
                $data[$header[$key]] = $value;
            }
            $row = $data;
//            $row['ORDER_NUMBER'];

            // Load the order
            $order = $this->_objectManager->create('Magento\Sales\Model\Order')
                ->loadByAttribute('increment_id', $row['ORDER_NUMBER']);
            //OR
//            $order = $this->_objectManager->create('Magento\Sales\Model\Order')
//                ->load('1');

            // Check if order has already shipped or can be shipped
            if (!$order->canShip()) {
//                throw new \Magento\Framework\Exception\LocalizedException(
//                    __('You can\'t create an shipment.')
//                );
                $this->messageManager->addError( __( $row['ORDER_NUMBER'].' order cannot be shipped.' ) );
                continue;
            }

            // Initialize the order shipment object
            $convertOrder = $this->_objectManager->create('Magento\Sales\Model\Convert\Order');
            $shipment = $convertOrder->toShipment($order);

            // Loop through order items
            foreach ($order->getAllItems() as $orderItem) {
            // Check if order item is virtual or has quantity to ship
                if (!$orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                    continue;
                }

                $qtyShipped = $orderItem->getQtyToShip();

            // Create shipment item with qty
                $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);

            // Add shipment item to shipment
                $shipment->addItem($shipmentItem);
            }

            // Register shipment
            $shipment->register();

            $track_data = array(
                'carrier_code' => $row['CARRIER_CODE'],
                'title' => $row['TITLE'],
                'number' => $row['NUMBER']
            );

            $shipment->getOrder()->setIsInProcess(true);

            try {
            // Save created shipment and order
                $track = $this->_objectManager->create('Magento\Sales\Model\Order\Shipment\TrackFactory')->create()->addData($track_data);
                $shipment->addTrack($track)->save();
                $shipment->save();
                $shipment->getOrder()->save();

            // Send email
//                $this->_objectManager->create('Magento\Shipping\Model\ShipmentNotifier')
//                    ->notify($shipment);

                $shipment->save();
            } catch (\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __($e->getMessage())
                );
            }
        }
    }

}
