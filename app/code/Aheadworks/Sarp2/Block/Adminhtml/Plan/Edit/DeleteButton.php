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
namespace Aheadworks\Sarp2\Block\Adminhtml\Plan\Edit;

use Aheadworks\Sarp2\Api\PlanRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class DeleteButton
 * @package Aheadworks\Sarp2\Block\Adminhtml\Plan\Edit
 */
class DeleteButton implements ButtonProviderInterface
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
     * @var PlanRepositoryInterface
     */
    private $planRepository;

    /**
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param PlanRepositoryInterface $planRepository
     */
    public function __construct(
        RequestInterface $request,
        UrlInterface $urlBuilder,
        PlanRepositoryInterface $planRepository
    ) {
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        $this->planRepository = $planRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        $data = [];
        $planId = $this->request->getParam('plan_id');
        if ($planId && $this->planRepository->get($planId)) {
            $confirmMessage = __('Are you sure you want to do this?');
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => sprintf(
                    "deleteConfirm('%s', '%s')",
                    $confirmMessage,
                    $this->urlBuilder->getUrl('*/*/delete', ['plan_id' => $planId])
                ),
                'sort_order' => 20
            ];
        }
        return $data;
    }
}
