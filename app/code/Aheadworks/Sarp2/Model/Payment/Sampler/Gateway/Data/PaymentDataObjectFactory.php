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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\Data;

use Aheadworks\Sarp2\Model\Payment\SamplerInfoInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class PaymentDataObjectFactory
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\Gateway\Data
 */
class PaymentDataObjectFactory
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * Creates Payment Data Object
     *
     * @param SamplerInfoInterface $paymentInfo
     * @return PaymentDataObjectInterface
     */
    public function create(SamplerInfoInterface $paymentInfo)
    {
        $data = [
            'profile' => $paymentInfo->getProfile(),
            'payment' => $paymentInfo
        ];

        return $this->objectManager->create(
            PaymentDataObject::class,
            $data
        );
    }
}
