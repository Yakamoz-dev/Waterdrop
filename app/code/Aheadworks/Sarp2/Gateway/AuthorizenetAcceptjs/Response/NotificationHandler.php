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
namespace Aheadworks\Sarp2\Gateway\AuthorizenetAcceptjs\Response;

use Aheadworks\Sarp2\Engine\Notification;
use Aheadworks\Sarp2\Engine\NotificationInterface;
use Aheadworks\Sarp2\Engine\Notification\Locator;
use Aheadworks\Sarp2\Engine\Notification\Persistence;
use Aheadworks\Sarp2\Gateway\AuthorizenetAcceptjs\SubjectReaderFactory;
use Magento\AuthorizenetAcceptjs\Gateway\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;

/**
 * Class NotificationHandler
 * @package Aheadworks\Sarp2\Gateway\AuthorizenetAcceptjs\Response
 */
class NotificationHandler implements HandlerInterface
{
    /**
     * @var SubjectReaderFactory
     */
    private $subjectReaderFactory;

    /**
     * @var Locator
     */
    private $notificationLocator;

    /**
     * @var Persistence
     */
    private $notificationPersistence;

    /**
     * @param SubjectReaderFactory $subjectReaderFactory
     * @param Locator $notificationLocator
     * @param Persistence $notificationPersistence
     */
    public function __construct(
        SubjectReaderFactory $subjectReaderFactory,
        Locator $notificationLocator,
        Persistence $notificationPersistence
    ) {
        $this->subjectReaderFactory = $subjectReaderFactory;
        $this->notificationLocator = $notificationLocator;
        $this->notificationPersistence = $notificationPersistence;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(array $handlingSubject, array $response)
    {
        /** @var SubjectReader $subjectReader */
        $subjectReader = $this->subjectReaderFactory->getInstance();
        $paymentDO = $subjectReader->readPayment($handlingSubject);

        /** @var Notification $notification */
        $notification = $this->notificationLocator->getNotification(
            NotificationInterface::TYPE_BILLING_SUCCESSFUL,
            $paymentDO->getOrder()->getId()
        );
        if ($notification) {
            $notification->setStatus(NotificationInterface::STATUS_READY);
            $this->notificationPersistence->save($notification);
        }
    }
}
