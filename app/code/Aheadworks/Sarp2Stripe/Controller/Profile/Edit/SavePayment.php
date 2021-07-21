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
 * @package    Sarp2Stripe
 * @version    1.0.5
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Controller\Profile\Edit;

use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Aheadworks\Sarp2\Api\ProfileRepositoryInterface;
use Aheadworks\Sarp2\Model\Payment\Sampler\Exception\ExceptionWithUnmaskedMessage;
use Aheadworks\Sarp2\Model\Profile\View\Action\Permission as ActionPermission;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Quote\Api\Data\AddressInterfaceFactory;
use Magento\Quote\Api\Data\PaymentInterfaceFactory;

/**
 * Class SavePayment
 *
 * @package Aheadworks\Sarp2Stripe\Controller\Profile\Edit
 */
class SavePayment extends \Aheadworks\Sarp2\Controller\Profile\Edit\SavePayment
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @param Context $context
     * @param ProfileRepositoryInterface $profileRepository
     * @param Session $customerSession
     * @param Registry $registry
     * @param ActionPermission $actionPermission
     * @param ProfileManagementInterface $profileManagement
     * @param DataObjectHelper $dataObjectHelper
     * @param PaymentInterfaceFactory $paymentFactory
     * @param AddressInterfaceFactory $addressFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        ProfileRepositoryInterface $profileRepository,
        Session $customerSession,
        Registry $registry,
        ActionPermission $actionPermission,
        ProfileManagementInterface $profileManagement,
        DataObjectHelper $dataObjectHelper,
        PaymentInterfaceFactory $paymentFactory,
        AddressInterfaceFactory $addressFactory,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct(
            $context,
            $profileRepository,
            $customerSession,
            $registry,
            $actionPermission,
            $profileManagement,
            $dataObjectHelper,
            $paymentFactory,
            $addressFactory
        );
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            try {
                $this->validate($data);
                $this->performSave($data);
                $this->messageManager->addSuccessMessage(__('Payment has been successfully saved.'));
                return $this->successResponse();
            } catch (LocalizedException $e) {
                $message = $e->getMessage();
                if ($e instanceof ExceptionWithUnmaskedMessage) {
                    $message = $e->getMessage();
                } elseif ($e->getPrevious() instanceof ExceptionWithUnmaskedMessage) {
                    $message = $e->getPrevious()->getMessage();
                }
                $this->messageManager->addSuccessMessage(__('Payment has been successfully saved.'));
                return $this->errorResponse($message);
            } catch (\Exception $e) {
                $this->messageManager->addSuccessMessage(__('Payment has been successfully saved.'));
                return $this->errorResponse(__('Something went wrong while saving payment.'));
            }
        }

        return $this->errorResponse(__('Something went wrong while saving payment.'));
    }

    /**
     * Create success json result object
     *
     * @param array $data
     * @return \Magento\Framework\Controller\Result\Json
     */
    private function successResponse($data = [])
    {
        $resultJson = $this->resultJsonFactory->create();
        $data['success'] = true;
        $data['message'] = $data['message'] ?? '';

        return $resultJson->setData($data);
    }

    /**
     * Create error json result object
     *
     * @param string $message
     * @param array $data
     * @return \Magento\Framework\Controller\Result\Json
     */
    private function errorResponse($message, $data = [])
    {
        $resultJson = $this->resultJsonFactory->create();
        $data['success'] = false;
        $data['message'] = $message instanceof \Magento\Framework\Phrase
            ? $message->render()
            : $message;

        return $resultJson->setData($data)->setHttpResponseCode(400);
    }
}
