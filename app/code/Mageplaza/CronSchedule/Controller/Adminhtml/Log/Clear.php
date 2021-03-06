<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_CronSchedule
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\CronSchedule\Controller\Adminhtml\Log;

use Magento\Framework\App\ResponseInterface;
use Mageplaza\CronSchedule\Controller\Adminhtml\AbstractLog;

/**
 * Class Clear
 * @package Mageplaza\CronSchedule\Controller\Adminhtml\Log
 */
class Clear extends AbstractLog
{
    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        $collection = $this->collectionFactory->create();

        $count = $collection->getSize();

        $collection->getConnection()->truncateTable($collection->getMainTable());

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $count));

        return $this->_redirect('*/*/');
    }
}
