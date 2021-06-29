<?php

namespace Ecopure\Gift\Controller\Adminhtml\Dataimport;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Store\Model\ScopeInterface;


class Save extends \Magento\Backend\App\Action
{

    protected $fileSystem;

    protected $uploaderFactory;

    protected $request;

    protected $adapterFactory;

    protected $logger;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        AdapterFactory $adapterFactory,
        \Ecopure\Gift\Logger\Logger $logger

    ) {
        parent::__construct($context);
        $this->fileSystem = $fileSystem;
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
        $this->adapterFactory = $adapterFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->logger = $logger;
    }

    public function execute()
    {

        if ( (isset($_FILES['importdata']['name'])) && ($_FILES['importdata']['name'] != '') )
        {
            try
            {
                $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'importdata']);
                $uploaderFactory->setAllowedExtensions(['csv', 'xls']);
                $uploaderFactory->setAllowRenameFiles(true);
                $uploaderFactory->setFilesDispersion(true);

                $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
                $destinationPath = $mediaDirectory->getAbsolutePath('Ecopure_Gift_IMPORTDATA');

                $result = $uploaderFactory->save($destinationPath);

                if (!$result)
                {
                    throw new LocalizedException
                    (
                        __('File cannot be saved to path: $1', $destinationPath)
                    );

                }
                else
                {
                    $importPath = 'Ecopure_Gift_IMPORTDATA'.$result['file'];

                    $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);

                    $destinationfilePath = $mediaDirectory->getAbsolutePath($importPath);

                    /* file read operation */

                    $f_object = fopen($destinationfilePath, "r");

                    $column = fgetcsv($f_object);

                    // column name must be same as the Sample file name

                    if($f_object)
                    {
                        if( ($column[0] == 'ro_productid') && ($column[1] == 'ro_model_no') && ($column[2] == 'ro_asin') && ($column[3] == 'ro_order_no') && ($column[4] == 'use_order') )
                        {

                            $count = 0;
                            $success = 0;
                            $failure = 0;

                            while (($columns = fgetcsv($f_object)) !== FALSE)
                            {

                                $rowData = $this->_objectManager->create('Ecopure\Gift\Model\Product');

                                if($columns[0] != 'ro_productid')// unique Name like Primary key
                                {
                                    $count++;

                                    if ($columns[1]=="" || $columns[2]=="" || $columns[6]=="") {
                                        $this->messageManager->addError(__('Error: Missing parameter %1 can not be import.', $count));
                                        $failure++;
                                        continue;
                                    }

                                    $columns[3] = preg_replace('/-/i','',$columns[3]);

                                    if ($columns[0]){
                                        $product = $this->_objectManager->create('Ecopure\Gift\Model\Product');
                                        $product->load($columns[0],'ro_productid');
                                        if ($product->getId()) {
                                            $message = __('Error: Product Id %1 duplicates', $columns[0]);
                                            $this->messageManager->addError($message);
                                            $this->logger->info($message);
                                            $failure++;
                                            continue;
                                        }
                                    }

                                    if ($columns[3]){
                                        $product = $this->_objectManager->create('Ecopure\Gift\Model\Product');
                                        $product->load($columns[3],'ro_order_no');
                                        if ($product->getId()) {
                                            $message = __('Error: Order No %1 duplicates', $columns[3]);
                                            $this->messageManager->addError($message);
                                            $this->logger->info($message);
                                            $failure++;
                                            continue;
                                        }
                                    }

                                    if ($columns[4] == 0) {
                                        if ($columns[0] == "" || $columns[1] == "" || $columns[2] == "") {
                                            $message = __('Error: Product Id Blank line %1 can not be import', $count);
                                            $this->messageManager->addError($message);
                                            $this->logger->info($message);
                                            $failure++;
                                            continue;
                                        }
                                    } elseif ($columns[4] == 1) {
                                        if ($columns[1] == "" || $columns[2] == "" || $columns[3] == "") {
                                            $message = __('Error: Order No Blank line %1 can not be import', $count);
                                            $this->messageManager->addError($message);
                                            $this->logger->info($message);
                                            $failure++;
                                            continue;
                                        }
                                    }

                                    /// here this are all the Getter Setter Method which are call to set value
                                    // the auto increment column name not used to set value

                                    $rowData->setRoProductid($columns[0]);

                                    $rowData->setRoModelNo($columns[1]);

                                    $rowData->setRoAsin($columns[2]);

                                    $rowData->setRoOrderNo($columns[3]);

                                    $rowData->setRoUsed(0);

                                    $rowData->setRoRegd(0);

                                    $rowData->setUseOrder($columns[4]);

                                    $rowData->setOnlyReg($columns[5]);

                                    $rowData->setCountry($columns[6]);

                                    $rowData->setCreatedAt(date('Y-m-d H:i:s'));

                                    $rowData->save();

                                    $success++;
                                }

                            }

                            $this->messageManager->addSuccess(__('Import Success: %1 successfully, %2 failed', $success, $failure));
                            $this->_redirect('ecopure_gift/product/index');
                        }
                        else
                        {
                            $this->messageManager->addError(__("invalid Formated File"));
                            $this->_redirect('ecopure_gift/dataimport/importdata');
                        }

                    }
                    else
                    {
                        $this->messageManager->addError(__("File hase been empty"));
                        $this->_redirect('ecopure_gift/dataimport/importdata');
                    }

                }

            }
            catch (\Exception $e)
            {
                $this->messageManager->addError(__($e->getMessage()));
                $this->_redirect('ecopure_gift/dataimport/importdata');
            }

        }
        else
        {
            $this->messageManager->addError(__("Please try again."));
            $this->_redirect('ecopure_gift/dataimport/importdata');
        }
    }
}
