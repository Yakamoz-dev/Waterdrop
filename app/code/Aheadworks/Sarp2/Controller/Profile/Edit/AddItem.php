<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Sarp2
 * @version    2.15.3
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Controller\Profile\Edit;

use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class AddItem
 * @package Aheadworks\Sarp2\Controller\Profile\Edit
 */
class AddItem extends Action
{
    /**
     * @var ProfileManagementInterface
     */
    private $profileManagement;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param ProfileManagementInterface $profileManagement
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        ProfileManagementInterface $profileManagement,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->profileManagement = $profileManagement;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $customerId = $this->customerSession->getCustomerId();
            $storeId = $this->storeManager->getStore()->getId();
            $this->profileManagement->addItemsFromQuoteToNearestProfile($customerId, $storeId);
            $url = $this->_url->getUrl('aw_sarp2/profile/index');
            $this->messageManager->addComplexSuccessMessage(
                'awSarp2NearestProfileUpdateSuccessMessage',
                ['url' => $url]
            );
        } catch (\Exception $e) {
            $this->messageManager->addWarningMessage(__('We can\'t do it right now.'));
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        return $this->resultRedirectFactory->create()->setPath('/');
    }
}
