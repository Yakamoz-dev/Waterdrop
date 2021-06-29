<?php
/**
 * Ecopure_Gift extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Ecopure
 * @package   Ecopure_Gift
 * @copyright 2016 Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru
 */
namespace Ecopure\Gift\Controller\Adminhtml\Gift;

use Ecopure\Gift\Controller\Adminhtml\Gift;
use Ecopure\Gift\Controller\RegistryConstants;

class Edit extends Gift
{
    /**
     * Initialize current author and set it in the registry.
     *
     * @return int
     */
    protected function _initGift()
    {
        $giftId = $this->getRequest()->getParam('id');
        $this->coreRegistry->register(RegistryConstants::CURRENT_GIFT_ID, $giftId);

        return $giftId;
    }

    /**
     * Edit or create author
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $giftId = $this->_initGift();

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Ecopure_Gift::giftro');
        $resultPage->getConfig()->getTitle()->prepend(__('Gift'));
        $resultPage->addBreadcrumb(__('Gifts'), __('Gifts'), $this->getUrl('ecopure_gift/gift'));

        if ($giftId === null) {
            $resultPage->addBreadcrumb(__('New Gift'), __('New Gift'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Gift'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Gift'), __('Edit Gift'));
            $resultPage->getConfig()->getTitle()->prepend('Gift: '.$giftId);
        }
        return $resultPage;
    }
}
