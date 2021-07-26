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
 * @package    Sarp2GraphQl
 * @version    1.0.2
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2GraphQl\Model\Resolver;

use Aheadworks\Sarp2\Api\PaymentInfoManagementInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class PaymentInfoForSubscription
 *
 * @package Aheadworks\Sarp2GraphQl\Model\Resolver
 */
class PaymentInfoForSubscription extends AbstractResolver
{
    /**
     * @var PaymentInfoManagementInterface
     */
    private $paymentInfoManagement;

    /**
     * @param PaymentInfoManagementInterface $paymentInfoManagement
     */
    public function __construct(
        PaymentInfoManagementInterface $paymentInfoManagement
    ) {
        $this->paymentInfoManagement = $paymentInfoManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function performResolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        return $this->paymentInfoManagement->getPaymentInfoForSubscription();
    }
}
