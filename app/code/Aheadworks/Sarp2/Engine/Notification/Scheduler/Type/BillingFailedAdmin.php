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
namespace Aheadworks\Sarp2\Engine\Notification\Scheduler\Type;

use Aheadworks\Sarp2\Engine\Notification;
use Aheadworks\Sarp2\Engine\Notification\Persistence;
use Aheadworks\Sarp2\Engine\Notification\SchedulerInterface;
use Aheadworks\Sarp2\Engine\NotificationFactory;
use Aheadworks\Sarp2\Engine\NotificationInterface;
use Aheadworks\Sarp2\Engine\Payment\Engine\Logger\DataFormatter\Entity\Exception as ExceptionFormatter;
use Aheadworks\Sarp2\Engine\Payment\Engine\Logger\DataFormatter\Entity\Payment as PaymentFormatter;
use Aheadworks\Sarp2\Model\Config;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime;

/**
 * Class BillingFailedAdmin
 *
 * @package Aheadworks\Sarp2\Engine\Notification\Scheduler\Type
 */
class BillingFailedAdmin implements SchedulerInterface
{
    const EXCEPTION = 'exception';

    /**
     * @var NotificationFactory
     */
    private $notificationFactory;

    /**
     * @var Persistence
     */
    private $persistence;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var PaymentFormatter
     */
    private $paymentFormatter;

    /**
     * @var ExceptionFormatter
     */
    private $exceptionFormatter;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @param NotificationFactory $notificationFactory
     * @param Persistence $persistence
     * @param DateTime $dateTime
     * @param Config $config
     * @param PaymentFormatter $paymentFormatter
     * @param ExceptionFormatter $exceptionFormatter
     * @param Json $serializer
     */
    public function __construct(
        NotificationFactory $notificationFactory,
        Persistence $persistence,
        DateTime $dateTime,
        Config $config,
        PaymentFormatter $paymentFormatter,
        ExceptionFormatter $exceptionFormatter,
        Json $serializer
    ) {
        $this->notificationFactory = $notificationFactory;
        $this->persistence = $persistence;
        $this->dateTime = $dateTime;
        $this->config = $config;
        $this->paymentFormatter = $paymentFormatter;
        $this->exceptionFormatter = $exceptionFormatter;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function schedule($sourcePayment, $additionalData)
    {
        $profile = $sourcePayment->getProfile();
        $storeId = $profile->getStoreId();
        $exception = $additionalData[self::EXCEPTION] ?? null;

        $toEmail = $this->getEmail($storeId);
        if (null == $toEmail) {
            return null;
        }

        $notification = $this->notificationFactory->create();
        $notification->setType(NotificationInterface::TYPE_BILLING_FAILED_ADMIN)
            ->setStatus(NotificationInterface::STATUS_READY)
            ->setEmail($toEmail)
            ->setName(null)
            ->setScheduledAt($this->dateTime->formatDate(true))
            ->setStoreId($storeId)
            ->setProfileId($sourcePayment->getProfileId());

        $data = [
            'profileId' => $profile->getProfileId(),
            'incrementProfileId' => $profile->getIncrementId(),
            'paymentId' => $sourcePayment->getId(),
            'paymentDetails' => $this->getPaymentDetails($sourcePayment),
            'exceptionMessage' => $exception
                ? $this->exceptionFormatter->format($exception)
                : null
        ];
        $notification->setNotificationData($data);

        try {
            $this->persistence->save($notification);
        } catch (CouldNotSaveException $exception) {
            return null;
        }

        return $notification;
    }

    /**
     * Retrieve receiver email
     *
     * @param int $soreId
     * @return string
     */
    private function getEmail($soreId)
    {
        $email = $this->config->getFailedBillingAdminEmail($soreId);
        if (!$email) {
            $emails = $this->config->getFailedBillingBCCEmail($soreId);
            if (!empty($emails)) {
                $email = reset($emails);
            }
        }

        return $email;
    }

    /**
     * Retrieve payment details array
     *
     * @param $payment
     * @return array
     */
    private function getPaymentDetails($payment)
    {
        try {
            $detailsJson = $this->paymentFormatter->format($payment);
            $details = $this->serializer->unserialize($detailsJson);
        } catch (\Exception $exception) {
            $details = [];
        }

        return $details;
    }
}
