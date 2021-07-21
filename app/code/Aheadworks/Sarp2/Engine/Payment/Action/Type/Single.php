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
 * @version    2.15.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2\Engine\Payment\Action\Type;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\Exception\ScheduledPaymentException;
use Aheadworks\Sarp2\Engine\Payment\Action\PlaceOrder;
use Aheadworks\Sarp2\Engine\Payment\Action\ResultFactory;
use Aheadworks\Sarp2\Engine\Payment\ActionInterface;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Aheadworks\Sarp2\Engine\Profile\PaymentInfoFactory;
use Aheadworks\Sarp2\Model\Profile\Exception\CouldNotConvertException;
use Aheadworks\Sarp2\Model\Profile\ToOrder;

/**
 * Class Single
 * @package Aheadworks\Sarp2\Engine\Payment\Action\Type
 */
class Single implements ActionInterface
{
    /**
     * @var ToOrder
     */
    private $converter;

    /**
     * @var PlaceOrder
     */
    private $placeOrderService;

    /**
     * @var PaymentInfoFactory
     */
    private $paymentInfoFactory;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @param ToOrder $converter
     * @param PlaceOrder $placeOrderService
     * @param PaymentInfoFactory $paymentInfoFactory
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        ToOrder $converter,
        PlaceOrder $placeOrderService,
        PaymentInfoFactory $paymentInfoFactory,
        ResultFactory $resultFactory
    ) {
        $this->converter = $converter;
        $this->placeOrderService = $placeOrderService;
        $this->paymentInfoFactory = $paymentInfoFactory;
        $this->resultFactory = $resultFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function pay(PaymentInterface $payment)
    {
        $profile = $payment->getProfile();
        $paymentPeriod = $payment->getPaymentPeriod();
        $order = $this->convertProfileToOrder($profile, $paymentPeriod);
        $this->placeOrderService->place(
            $order,
            [
                $this->paymentInfoFactory->create(
                    [
                        'profile' => $profile,
                        'paymentPeriod' => $paymentPeriod
                    ]
                )
            ]
        );
        $profile
            ->setOrder($order)
            ->setLastOrderId($order->getEntityId())
            ->setLastOrderDate($order->getCreatedAt());
        return $this->resultFactory->create(['order' => $order]);
    }

    /**
     * Convert profile to order
     *
     * @param ProfileInterface $profile
     * @param string $paymentPeriod
     * @return \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order
     * @throws ScheduledPaymentException
     */
    private function convertProfileToOrder($profile, $paymentPeriod)
    {
        try {
            return $this->converter->convert($profile, $paymentPeriod);
        } catch (\Exception $e) {
            throw new ScheduledPaymentException(__($e->getMessage()), $e);
        }
    }
}
