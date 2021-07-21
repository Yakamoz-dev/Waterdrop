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
namespace Aheadworks\Sarp2\Engine\Payment\Engine\Logger\DataFormatter\Entity;

use Aheadworks\Sarp2\Engine\Payment as PaymentModel;
use Aheadworks\Sarp2\Engine\PaymentInterface;
use Aheadworks\Sarp2\Engine\Payment\Engine\Logger\DataFormatterInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Payment
 * @package Aheadworks\Sarp2\Engine\Payment\Engine\Logger\DataFormatter\Entity
 */
class Payment implements DataFormatterInterface
{
    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var array
     */
    private $fieldsToLog = [
        'item_id',
        'type',
        'payment_period',
        'payment_status',
        'scheduled_at',
        'paid_at',
        'retry_at',
        'retries_count',
        'total_scheduled',
        'base_total_scheduled',
        'total_paid',
        'base_total_paid'
    ];

    /**
     * @param Json $serializer
     */
    public function __construct(Json $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function format($subject)
    {
        if ($subject instanceof PaymentInterface) {
            /** @var PaymentModel $subject */
            $rawData = array_intersect_key($subject->getData(), array_flip($this->fieldsToLog));
            $rawData['profile_id'] = $subject->getProfileId();
            return $this->serializer->serialize(
                $this->preparePaymentData($rawData)
            );
        }
        return '';
    }

    /**
     * Prepare payment data
     *
     * @param $rawData
     * @return mixed
     */
    private function preparePaymentData($rawData)
    {
        foreach ($rawData as $index => $rawValue) {
            if (in_array($index, ['paid_at', 'retry_at']) && !$rawValue
                || $index == 'retries_count' && $rawValue == 0
            ) {
                unset($rawData[$index]);
            }
        }
        return $rawData;
    }
}
