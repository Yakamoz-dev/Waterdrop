<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-fraud-check
 * @version   1.1.5
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */




namespace Mirasvit\FraudCheck\Controller\Adminhtml\Rule;


use Magento\Framework\Controller\ResultFactory;
use Mirasvit\FraudCheck\Controller\Adminhtml\Rule;

class Duplicate extends Rule
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $id = $this->getRequest()->getParam('id');
        $model = $this->initModel();
        
        $model->setId(null);

        $this->initPage($resultPage)
            ->getConfig()->getTitle()->prepend( __('New Rule'));

        return $resultPage;
    }
}