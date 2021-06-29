<?php
/**
 * Copyright © Mageside. All rights reserved.
 * See MS-LICENSE.txt for license details.
 */
namespace Mageside\KeepAdminAuthorized\Controller\Adminhtml\Authorize;

use \Magento\Framework\Controller\ResultFactory;

class Keep extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $resultJson->setData("Ok");
    }
}
