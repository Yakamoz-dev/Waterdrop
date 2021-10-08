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
namespace Aheadworks\Sarp2\Model\Payment\Sampler\OfflinePayment;

use Aheadworks\Sarp2\Model\Payment\SamplerInfoInterface;
use Aheadworks\Sarp2\Model\Payment\SamplerInterface;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\PaymentInterface as QuoteInfoInterface;

/**
 * Class Adapter
 * @package Aheadworks\Sarp2\Model\Payment\Sampler\OfflinePayment
 */
class Adapter implements SamplerInterface
{
    /**
     * @var int
     */
    private $storeId;

    /**
     * {@inheritdoc}
     */
    public function assignData(SamplerInfoInterface $samplerPaymentInfo, DataObject $data)
    {
        return $samplerPaymentInfo
            ->setAdditionalInformation('aw_sarp_payment_token_id', null)
            ->setAdditionalInformation('aw_sarp_skip_payment_token', true);
    }

    /**
     * {@inheritdoc}
     */
    public function place(SamplerInfoInterface $samplerPaymentInfo, QuoteInfoInterface $quotePaymentInfo)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function revert(SamplerInfoInterface $samplerPaymentInfo)
    {
        return $this;
    }

    /**
     * Is active
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isActive($storeId = null)
    {
        return true;
    }

    /**
     * Check authorize availability
     *
     * @return bool
     */
    public function canAuthorize()
    {
        return true;
    }

    /**
     * Check void command availability
     *
     * @return bool
     */
    public function canVoid()
    {
        return true;
    }

    /**
     * Set store id
     *
     * @param int $storeId
     * @return void
     */
    public function setStore($storeId)
    {
        $this->storeId = (int)$storeId;
    }

    /**
     * Get store id
     *
     * @return int
     */
    public function getStore()
    {
        return $this->storeId;
    }
}
