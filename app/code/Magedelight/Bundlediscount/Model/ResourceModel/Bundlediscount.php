<?php

/**
 * Magedelight
 * Copyright (C) 2016 Magedelight <info@magedelight.com>.
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html.
 *
 * @category Magedelight
 *
 * @copyright Copyright (c) 2016 Mage Delight (http://www.magedelight.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author Magedelight <info@magedelight.com>
 */

namespace Magedelight\Bundlediscount\Model\ResourceModel;

use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\DirectoryList;

use Magedelight\Bundlediscount\Helper\Data;
use Magedelight\Bundlediscount\Model\BundlediscountFactory;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\GroupFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Psr\Log\LoggerInterface;

class Bundlediscount extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Errors in import process.
     *
     * @var array
     */
    private $importErrors = [];

    /**
     * Filesystem instance.
     *
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * Customer factory.
     *
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $customerFactory;
    private $exportHeaderColumn = ['bundle_id', 'base_sku', 'bundle_name', 'base_qty', 'discount_type',
        'discount_price', 'status', 'exclude_base_product', 'sort_order', 'store_ids', 'customer_groups',
        'start_from', 'ends_on', 'item_sku', 'item_qty', 'item_sort_order', 'bundle_option', 'bundle_tags'];
    private $bundleExportIgnoreColumns = ['created_at', 'updated_at','bundle_keywords'];
    private $bundleColumnMaps = [
        'bundle_id' => 'bundle_id',
        'product_id' => 'base_sku',
        'name' => 'bundle_name',
        'qty' => 'base_qty',
        'discount_type' => 'discount_type',
        'discount_price' => 'discount_price',
        'status' => 'status',
        'exclude_base_product' => 'exclude_base_product',
        'sort_order' => 'sort_order',
        'store_ids' => 'store_ids',
        'customer_groups' => 'customer_groups',
        'date_from' => 'start_from',
        'date_to' => 'ends_on',
        'bundle_option' => 'bundle_option',
        'bundle_tags' => 'bundle_tags'
    ];
    private $itemsColumnMaps = [
        'product_id' => 'item_sku',
        'qty' => 'item_qty',
        'sort_order' => 'item_sort_order',
    ];
    private $discountLabels = [
        'Fixed' => 0,
        'Percentage' => 1,
    ];
    private $storeMaps = [];
    private $groupsMap = [];
    protected $helper;
    private $itemExportIgnoreColumns = ['item_id', 'bundle_id'];
    private $importHeaderColumns = [];
    private $datavalidationMessages = [];
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resources;

    /**
     * Bundlediscount constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param Product $product
     * @param CustomerFactory $customerFactory
     * @param BundlediscountFactory $bundlediscount
     * @param \Magedelight\Bundlediscount\Model\Bundleitems $bundleitems
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param Data $helper
     * @param GroupFactory $collectionFactory
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        Product $product,
        CustomerFactory $customerFactory,
        BundlediscountFactory $bundlediscount,
        \Magedelight\Bundlediscount\Model\Bundleitems $bundleitems,
        \Magedelight\Bundlediscount\Model\BundleitemsFactory $bundleitemsFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        Data $helper,
        GroupFactory $collectionFactory,
        $connectionName = null
    ) {
        $this->_productModel = $product;
        $this->filesystem = $filesystem;
        $this->customerFactory = $customerFactory;
        $this->bundlediscount = $bundlediscount;
        $this->bundleitemsFactory = $bundleitemsFactory;
        $this->bundleitems = $bundleitems;
        $this->uploaderFactory = $uploaderFactory;
        $this->helper = $helper;
        $this->resources = $resource;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $connectionName);
    }

    public function _construct()
    {
        $this->_init('md_bundlediscount_bundles', 'bundle_id');
        $this->storeMaps = $this->helper->getStoreCodeMaps();
        $this->groupsMap = $this->collectionFactory->create()->getCollection()->load()->toOptionHash();
    }

    public function uploadAndImport(\Magento\Framework\DataObject $object)
    {
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => 'bundlediscountimport']);
            $uploader->setAllowedExtensions(['csv']);
        } catch (\Exception $e) {
            if ($e->getCode() == '666') {
                return $this;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
            }
        }

        $csvFile = $uploader->validateFile()['tmp_name'];
        $this->importErrors = [];
        $tmpDirectory = $this->filesystem->getDirectoryRead(DirectoryList::SYS_TMP);
        $path = $tmpDirectory->getRelativePath($csvFile);
        $stream = $tmpDirectory->openFile($path);

        // check and skip headers
        $headers = $stream->readCsv();
        if ($headers === false || count($headers) < 1) {
            $stream->close();
            throw new \Magento\Framework\Exception\LocalizedException(__('Please correct Bundle Discount File Format.'));
        }

        $this->importHeaderColumns = $headers;
        $isValidateColumns = $this->_validateColumns($headers);
        $connection = $this->getConnection();
        $connection->beginTransaction();

        try {
            if ($isValidateColumns) {
                $rowNumber = 0;
                $data = [];
                while (false !== ($csvLine = $stream->readCsv())) {
                    $isBundle = $this->isBundlesRow($csvLine);
                    if ($isBundle) {
                        ++$rowNumber;
                        $isValid = true;
                        $isSelectionTrue = true;
                        $itemRow = 0;
                    }
                    foreach ($csvLine as $key => $value) {
                        $columnName = ($isBundle) ? array_search(
                            $this->importHeaderColumns[$key],
                            $this->bundleColumnMaps
                        ) : array_search(
                            $this->importHeaderColumns[$key],
                            $this->itemsColumnMaps
                        );

                        if ($isBundle) {
                            if (is_string($columnName) && strlen($columnName) > 0) {
                                $data[$rowNumber][$columnName] = $this->getMappedValues(
                                    $value,
                                    $this->importHeaderColumns[$key],
                                    'import',
                                    true,
                                    $isValid
                                );
                                if (!$isValid) {
                                    unset($data[$rowNumber]);
                                }
                            }
                        } else {
                            if (is_string($columnName) && strlen($columnName) > 0 && $isValid) {
                                $data[$rowNumber]['selections'][$itemRow][$columnName] = $this->getMappedValues(
                                    $value,
                                    $this->importHeaderColumns[$key],
                                    'import',
                                    false,
                                    $isSelectionTrue
                                );
                                if (!$isSelectionTrue) {
                                    unset($data[$rowNumber]['selections'][$itemRow]);
                                    continue;
                                }
                            }
                        }
                    }

                    if (!$isBundle) {
                        ++$itemRow;
                    }
                }
                $this->saveBundlesData($data);
                foreach ($this->datavalidationMessages as $invalidMessage) {
                    $this->importErrors[] = $invalidMessage;
                }
            } else {
                foreach ($this->_errorMessages as $message) {
                    $this->importErrors[] = $message;
                }
                return false;
            }
        } catch (\Exception $e) {
            $connection->rollback();
            $message = __('There is an issue while uploading csv, Please check csv file.');
            throw new \Magento\Framework\Exception\LocalizedException($message);
            /* commenting this code because of method is undefined.
                method exist in Magento\Framework\Filesystem\Io\File  */
            /*$stream->streamClose();*/
        } catch (\Exception $e) {
            $connection->rollback();
            $stream->streamClose();
            throw new \Magento\Framework\Exception\LocalizedException('An error occurred while import data.');
        }
        $connection->commit();
        if ($this->importErrors) {
            $error = __(
                'We couldn\'t import this file because of these errors: %1',
                implode(" \n", $this->importErrors)
            );
            throw new \Magento\Framework\Exception\LocalizedException($error);
        }
        return $this;
    }

    protected function _validateColumns($columns)
    {
        $invalidColumns = [];
        $validColumns = true;
        foreach ($columns as $column) {
            if (!in_array($column, $this->exportHeaderColumn)) {
                $invalidColumns[] = $column;
            }
        }
        if (count($invalidColumns) > 0) {
            $this->importErrors[] = __(
                'Invalid columns %1 specified in import file',
                implode(', ', $invalidColumns)
            );
            $validColumns = false;
        }
        return $validColumns;
    }

    protected function isBundlesRow($row)
    {
        $isBundle = false;
        $itemsRow = array_values($this->itemsColumnMaps);
        $itemSkuKey = (in_array('item_sku', $this->importHeaderColumns)) ? array_search(
            'item_sku',
            $this->importHeaderColumns
        ) : null;
        $itemQtyKey = (in_array('item_qty', $this->importHeaderColumns)) ? array_search(
            'item_qty',
            $this->importHeaderColumns
        ) : null;
        $itenSortOrderKey = (in_array(
            'item_sort_order',
            $this->importHeaderColumns
        )) ? array_search(
            'item_sort_order',
            $this->importHeaderColumns
        ) : null;

        if (!$itemSkuKey && !$itemQtyKey && !$itenSortOrderKey) {
            $isBundle = true;
        } else {
            $itemSkuValue = (!is_null($itemSkuKey)) ? $row[$itemSkuKey] : '';
            if (strlen($itemSkuValue) <= 0 && strlen($itemSkuValue) <= 0 && strlen($itemSkuValue) <= 0) {
                $isBundle = true;
            }
        }
        return $isBundle;
    }

    public function saveBundlesData($data)
    {
        $index = 1;
        try {
            foreach ($data as $bundle) {
                $selections = isset($bundle['selections']) ? $bundle['selections'] : [];
                unset($bundle['selections']);
                $isValid = true;
                $this->_validateRowData($bundle, $index, $isValid);
                if (!$isValid) {
                    continue;
                }

                if (isset($bundle['bundle_id']) && trim($bundle['bundle_id'], '"') != '') {
                    $bundleModel = $this->bundlediscount->create()->load($bundle['bundle_id']);
                }

                /* If the date from and date to are empty then unset it from post data */
                if (array_key_exists('date_from', $bundle) && ($bundle['date_from'] == '' || $bundle['date_from'] == '""')) {
                    unset($bundle['date_from']);
                }
                if (array_key_exists('date_to', $bundle) && ($bundle['date_to'] == '' || $bundle['date_to'] == '""')) {
                    unset($bundle['date_to']);
                }

                if ($bundle['bundle_id'] == '') {
                    unset($bundle['bundle_id']);
                    $bundleModel = $this->bundlediscount->create();
                } else {
                    $bundleModel = $this->bundlediscount->create()->load($bundle['bundle_id']);
                }

                $bundleModel->setData('discount_type', $bundle['discount_type'])
                    ->setData('name', $bundle['name'])
                    ->setData('discount_price', $bundle['discount_price'])
                    ->setData('status', $bundle['status'])
                    ->setData('exclude_base_product', $bundle['exclude_base_product'])
                    ->setData('bundle_option', $bundle['bundle_option'])
                    ->setData('bundle_tags', $bundle['bundle_tags'])
                    ->setData('customer_groups', $bundle['customer_groups'])
                    ->setData('store_ids', $bundle['store_ids'])
                    ->setData('sort_order', $bundle['sort_order'])
                    ->setData('product_id', $bundle['product_id'])
                    ->setData('updated_at', date('Y-m-d H:i:s'))
                    ->setData('date_from', isset($bundle['date_from']) ? date('l jS F Y', strtotime($bundle['date_from'])) : '')
                    ->setData('date_to', isset($bundle['date_to']) ? date('l jS F Y', strtotime($bundle['date_to'])) : '')
                    ->setData('qty', $bundle['qty']);
                $bundleModel->save();
                $lastBundleId = $bundleModel->getBundleId();

                foreach ($selections as $selection) {
                    $isSelectionValid = true;
                    $this->_validateRowData($selection, $index, $isSelectionValid);
                    if (!$isSelectionValid) {
                        continue;
                    }
                    $selection['bundle_id'] = $lastBundleId;

                    $itemResult = $this->bundleitems->getCollection()
                        ->addFieldToSelect('item_id')
                        ->addFieldToFilter('bundle_id', $lastBundleId)
                        ->addFieldToFilter('product_id', $selection['product_id']);

                    $itemsModel = $this->bundleitemsFactory->create();
                    $itemsModel->setData('bundle_id', $selection['bundle_id'])
                        ->setData('product_id', $selection['product_id'])
                        ->setData('qty', $selection['qty'])
                        ->setData('sort_order', $selection['sort_order']);
                    if ($itemResult->getSize()) {
                        $itemId = $itemResult->getFirstItem()->getItemId();
                        $itemsModel->setId($itemId);
                    }
                    $itemsModel->save();
                }
                ++$index;
            }
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException($e->getMessage());
        }
    }

    protected function _validateRowData($data, $index, &$isValid)
    {
        if (isset($data['selections'])) {
            $selections = $data['selections'];
        } else {
            $selections = [];
        }
        unset($data['selections']);
        $dateRegx = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/';
        foreach ($data as $key => $value) {
            $value = trim($value, '"');
            switch ($key) {
                case 'discount_price':
                    $value = (is_numeric($value)) ? (float) $value : $value;
                    if (!is_numeric($value) || $value <= 0) {
                        $isValid = false;
                        $this->datavalidationMessages[] = __('Invalid value %1 for column %2 at row %3', $key, $value, $index);
                    }
                    break;
                case 'status':
                    $value = (is_numeric($value)) ? (int) $value : $value;
                    if ($value != 1 && $value != 0) {
                        $isValid = false;
                        $this->datavalidationMessages[] = __('Invalid value %1 for column %2 at row %3', $key, $value, $index);
                    }
                    break;
                case 'exclude_base_product':
                    $value = (is_numeric($value)) ? (int) $value : $value;
                    if ($value != 1 && $value != 0) {
                        $isValid = false;
                        $this->datavalidationMessages[] = __('Invalid value %1 for column %2 at row %3', $key, $value, $index);
                    }
                    break;
                case 'date_from':
                    if (strlen($value) > 0) {
                        $value =  date("Y-m-d", strtotime($value));
                        if (!preg_match($dateRegx, $value)) {
                            $isValid = false;
                            $this->datavalidationMessages[] = __("Invalid value %1 for column %2 at row %3.Date should be in format of 'YYYY-mm-dd'.", $this->bundleColumnMaps[$key], $value, $index);
                        }
                    }
                    break;
                case 'date_to':
                    if (strlen($value) > 0) {
                        $value = date("Y-m-d", strtotime($value));
                        if (!preg_match($dateRegx, $value)) {
                            $isValid = false;
                            $this->datavalidationMessages[] = __("Invalid value %1 for column %2 at row %3.Date should be in format of 'YYYY-mm-dd'.", $this->bundleColumnMaps[$key], $value, $index);
                        } else {
                            $fromDate = trim($data['date_from'], '"');
                            if (preg_match($dateRegx, $fromDate)) {
                                $fromTimestamp = strtotime($fromDate);
                                $toTimestamp = strtotime($value);
                                if ($toTimestamp < $fromTimestamp) {
                                    $isValid = false;
                                    $this->datavalidationMessages[] = __("Date values are invalid for columns '%1' and '%2' at row %3.'%4' should be less than '%5'.", $this->bundleColumnMaps[$key], $this->bundleColumnMaps['date_from'], $index, $this->bundleColumnMaps[$key], $this->bundleColumnMaps['date_from']);
                                }
                            }
                        }
                    }
                    break;
            }
        }

        foreach ($selections as $i => $selection) {
            foreach ($selection as $key => $value) {
                switch ($key) {
                    case 'product_id':
                        if (!is_numeric($value) || $value <= 0) {
                            $isValid = false;
                            $this->datavalidationMessages[] = __('Invalid value %1 for column %2.', $key, $value);
                        }
                        break;
                    case 'qty':
                        if (!is_numeric($value) || $value <= 0) {
                            $isValid = false;
                            $this->datavalidationMessages[] = __('Invalid value %1 for column %2.', $key, $value);
                        }
                        break;
                }
            }
        }

        return $this;
    }

    protected function _getImportRow($row, $rowNumber = 0, $headers)
    {
        if (count($row) < 4) {
            $this->importErrors[] = __('Please correct Table Rates format in the Row #%1.', $rowNumber);
            return false;
        }
        $emailKey = array_search('email', $headers);
        $skuKey = array_search('sku', $headers);
        $qtyKey = array_search('qty', $headers);
        $priceKey = array_search('price', $headers);
        $websiteKey = array_search('website', $headers);
        // strip whitespace from the beginning and end of each row
        foreach ($row as $k => $v) {
            $row[$k] = trim($v);
        }

        $email = $row[$emailKey];
        $sku = $row[$skuKey];
        $qty = $row[$qtyKey];
        if ($websiteKey) {
            $websiteId = $row[$websiteKey];
        }
        $newprice = $row[$priceKey];
        $logprice = $row[$priceKey];
        if (!is_numeric($qty)) {
            $this->importErrors[] = __('Invalid Qty Price "%1" in the Row #%2.', $row[$qtyKey], $rowNumber);
            return false;
        } else {
            if ($qty <= 0) {
                $this->importErrors[] = __('Qty should be greater than 0 in the Row #%1.', $rowNumber);
                return false;
            }
        }
        $matches = [];
        if (!is_numeric($newprice)) {
            preg_match('/(.*)%/', $newprice, $matches);
            if ((is_array($matches) && count($matches) <= 0) || !is_numeric($matches[1])) {
                $this->importErrors[] = __('Invalid New Price "%1" in the Row #%2.', $row[$priceKey], $rowNumber);
                return false;
            } elseif (is_numeric($matches[1]) && ($matches[1] <= 0 || $matches[1] > 100)) {
                $this->importErrors[] = __('Invalid New Price "%1" in the Row #%2.Percentage should be greater than 0 and less or equals than 100.', $row[$priceKey], $rowNumber);
                return false;
            }
        }

        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
            $this->importErrors[] = __('Invalid email "%1" in the Row #%2.', $row[$emailKey], $rowNumber);
            return false;
        }

        if ($websiteKey) {
            $customer = $this->customerFactory->create()->getCollection()
            ->addNameToSelect()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('group_id')
            ->addFieldToFilter('email', $email)
            ->addFieldToFilter('website_id', $websiteId)
            ->getFirstItem();
        } else {
            $customer = $this->customerFactory->create()->getCollection()
            ->addNameToSelect()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('group_id')
            ->addFieldToFilter('email', $email)
            ->getFirstItem();
        }

        $customerId = $customer->getId();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->loadByAttribute('sku', $sku);

        if ($product->getTypeId() == 'grouped' || $product->getTypeId() == 'bundle' ||
            $product->getTypeId() == 'configurable') {
            $this->importErrors[] = __(
                '%1 Products are not allowed in the row #%2.',
                ucfirst($product->getTypeId()),
                $rowNumber
            );
            return false;
        }

        $productName = $product->getName();
        $productId = $product->getId();
        $price = $product->getPrice();
        if (is_array($matches) && count($matches) > 0) {
            if ($product->getTypeId() != 'bundle') {
                $newprice = $product->getPrice() - ($product->getPrice() * ($matches[1] / 100));
            } else {
                if ($matches[1] < 0 || $matches[1] > 100) {
                    $this->importErrors[] = __('Invalid New Price "%1" in the row #%2.Percentage should be greater than 0 and less or equals than 100.', $newprice, $rowNumber);
                    return false;
                } else {
                    $newprice = $matches[1];
                }
            }
        } else {
            if ($product->getTypeId() == 'bundle') {
                if ($newprice < 0 || $newprice > 100) {
                    $this->importErrors[] = __('Invalid New Price "%1" in the row #%2.Percentage should be greater than 0 and less or equals than 100.', $newprice, $rowNumber);
                    return false;
                }
            }
        }

        return [
            'customer_id' => $customerId,
            'customer_name' => $customer->getName(),
            'customer_email' => $email,
            'product_id' => $productId,
            'product_name' => $productName,
            'price' => $price,
            'log_price' => ($product->getTypeId() != 'bundle') ? $logprice : str_replace(
                '%',
                '',
                $logprice
            ),
            'new_price' => ($product->getTypeId() != 'bundle') ? $newprice : str_replace(
                '%',
                '',
                $newprice
            ), // New price for customer
            'qty' => $qty,
        ];
    }

    public function getExportData()
    {
        $data = [];
        $mainTable = $this->resources->getTableName('md_bundlediscount_bundles');
        $itemsTable = $this->resources->getTableName('md_bundlediscount_items');

        $connection = $this->resources->getConnection();
        $select = $connection->select()->from(['o' => $mainTable]);
        $bundles = $connection->fetchAll($select);
        $data[0] = $this->exportHeaderColumn;
        $index = 1;

        foreach ($bundles as $bundle) {
            $bunch = $this->getBlankBunch();
            foreach ($bundle as $key => $value) {
                if (in_array($key, $this->bundleExportIgnoreColumns)) {
                    continue;
                }
                $column = $this->bundleColumnMaps[$key];

                $bKey = array_search($column, $this->exportHeaderColumn);
                $bunch[$bKey] = $this->getMappedValues($value, $key, 'export', true);
            }

            $data[$index] = $bunch;
            ++$index;

            $itemsQuery = $connection->select()->from(['o' => $itemsTable])->where(
                'o.bundle_id=?',
                $bundle['bundle_id']
            );

            $items = $connection->fetchAll($itemsQuery);
            foreach ($items as $item) {
                $iBunch = $this->getBlankBunch();
                foreach ($item as $iKey => $iValue) {
                    if (in_array($iKey, $this->itemExportIgnoreColumns)) {
                        continue;
                    }
                    $iColumn = $this->itemsColumnMaps[$iKey];
                    $key = array_search($iColumn, $this->exportHeaderColumn);
                    $iBunch[$key] = $this->getMappedValues($iValue, $iKey, 'export', false);
                }
                $data[$index] = $iBunch;
                ++$index;
            }
        };
        return $data;
    }

    public function getMappedValues($value, $column = null, $type = 'export', $isBundle = true, $isValid = false)
    {
        $tmpArray = [];
        $returnValue = $value;
        if (!$column) {
            return $value;
        }
        if ($type == 'export') {
            $column = ($isBundle) ? $this->bundleColumnMaps[$column] : $this->itemsColumnMaps[$column];
        }
        $wrongValues = [];
        switch ($column) {
            case 'discount_type':
                $returnValue = ($type == 'export') ? array_search(
                    $value,
                    $this->discountLabels
                ) : $this->discountLabels[$value];
                if ($type != 'export') {
                    if ($returnValue !== 1 && $returnValue !== 0) {
                        $wrongValues[] = $value;
                    }
                }
                break;
            case 'store_ids':
                if ($type != 'export') {
                    $store_ids = explode('||', $value);
                } else {
                    $store_ids = explode(',', $value);
                }
                foreach ($store_ids as $storeId) {
                    $tmpArray[] = ($type == 'export') ? $this->storeMaps[$storeId] : array_search(
                        $storeId,
                        $this->storeMaps
                    );
                    if ($type != 'export') {
                        if (!is_numeric($tmpArray[count($tmpArray) - 1])) {
                            $wrongValues[] = $storeId;
                            array_pop($tmpArray);
                        }
                    }
                }
                if ($type != 'export') {
                    $returnValue = implode(',', $tmpArray);
                } else {
                    $returnValue = implode('||', $tmpArray);
                }
                break;
            case 'customer_groups':
                if ($type != 'export') {
                    $customer_groups = explode('||', $value);
                } else {
                    $customer_groups = explode(',', $value);
                }
                foreach ($customer_groups as $group) {
                    $tmpArray[] = ($type == 'export') ? $this->groupsMap[$group] : array_search(
                        $group,
                        $this->groupsMap
                    );
                    if ($type != 'export') {
                        if (!is_numeric($tmpArray[count($tmpArray) - 1])) {
                            $wrongValues[] = $group;
                            array_pop($tmpArray);
                        }
                    }
                }
                if ($type != 'export') {
                    $returnValue = implode(',', $tmpArray);
                } else {
                    $returnValue = implode('||', $tmpArray);
                }
                break;
            case 'base_sku':
                if ($type == 'export') {
                    $returnValue = $this->_productModel->load($value)->getSku();
                } else {
                    if ($this->_productModel->getIdBySku($value)) {
                        $returnValue = $this->_productModel->getIdBySku($value);
                    } else {
                        $returnValue = 0;
                        $wrongValues[] = $value;
                    }
                }
                if ($type != 'export') {
                    if (!is_numeric($returnValue)) {
                        $wrongValues[] = $value;
                    }
                }
                break;
            case 'item_sku':
                if ($type == 'export') {
                    $returnValue = $this->_productModel->load($value)->getSku();
                } else {
                    if ($this->_productModel->getIdBySku($value)) {
                        $returnValue = $this->_productModel->getIdBySku($value);
                    } else {
                        $returnValue = 0;
                        $wrongValues[] = $value;
                    }
                }
                if ($type != 'export') {
                    if (!is_numeric($returnValue)) {
                        $wrongValues[] = $value;
                    }
                }
                break;
            case 'bundle_tags':
                if ($type == 'export') {
                    $tempArray = explode(',', $value);
                    $returnValue = implode('||', $tempArray);
                } else {
                    $tempArray = explode('||', $value);
                    $returnValue = implode(',', $tempArray);
                }
                break;
            default:
                $returnValue = $value;
                break;
        }

        if (($type != 'export') && (!empty($wrongValues))) {
            $isValid = false;
            $this->datavalidationMessages[] = __(
                'Invalid value \'%1\' for column \'%2\' in uploaded file.',
                implode(',', $wrongValues),
                $column
            );
        }
        return ($type == 'export') ? $returnValue : $returnValue;
    }

    public function getBlankBunch()
    {
        return ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
    }
}
