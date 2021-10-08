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
namespace Aheadworks\Sarp2\Block\Adminhtml\Subscription\Edit;

use Aheadworks\Sarp2\Api\Data\ScheduledPaymentInfoInterface;
use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Aheadworks\Sarp2\Model\Profile\Source\Status;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class CancelButton
 * @package Aheadworks\Sarp2\Block\Adminhtml\Subscription\Edit
 */
class CancelButton implements ButtonProviderInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var ProfileManagementInterface
     */
    private $profileManagement;

    /**
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param ProfileManagementInterface $profileManagement
     */
    public function __construct(
        RequestInterface $request,
        UrlInterface $urlBuilder,
        ProfileManagementInterface $profileManagement
    ) {
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        $this->profileManagement = $profileManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        $data = [];
        $profileId = $this->request->getParam('profile_id');
        if ($profileId) {
            $allowedStatuses = $this->profileManagement->getAllowedStatuses($profileId);
            $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profileId);

            if (in_array(Status::CANCELLED, $allowedStatuses)
                && $nextPaymentInfo->getPaymentStatus()
                    != ScheduledPaymentInfoInterface::PAYMENT_STATUS_LAST_PERIOD_HOLDER
            ) {
                $data = [
                    'label' => __('Cancel Subscription'),
                    'class' => 'save',
                    'on_click' => sprintf(
                        "deleteConfirm('%s', '%s')",
                        __('Are you sure you want to do this?'),
                        $this->urlBuilder->getUrl('*/*/cancel', ['profile_id' => $profileId])
                    ),
                    'sort_order' => 40
                ];
            }
        }
        return $data;
    }
}
