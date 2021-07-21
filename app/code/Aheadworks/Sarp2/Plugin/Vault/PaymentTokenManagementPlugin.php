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
namespace Aheadworks\Sarp2\Plugin\Vault;

use Aheadworks\Sarp2\Model\Vault\Data\BackupManagement as VaultBackupManagement;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Model\PaymentTokenManagement;

/**
 * Class PaymentTokenManagementPlugin
 *
 * @package Aheadworks\Sarp2\Plugin\Vault
 */
class PaymentTokenManagementPlugin
{
    /**
     * @var VaultBackupManagement
     */
    private $vaultBackupManagement;

    /**
     * PaymentTokenManagementPlugin constructor.
     *
     * @param VaultBackupManagement $backupManagement
     */
    public function __construct(
        VaultBackupManagement $backupManagement
    ) {
        $this->vaultBackupManagement = $backupManagement;
    }

    /**
     * Before plugin for saveTokenWithPaymentLink
     *
     * @param PaymentTokenManagement $subject
     * @param PaymentTokenInterface $token
     * @param OrderPaymentInterface $payment
     * @return array
     */
    public function beforeSaveTokenWithPaymentLink(
        PaymentTokenManagement $subject,
        PaymentTokenInterface $token,
        OrderPaymentInterface $payment
    ) {
        $tokenDuplicate = $subject->getByPublicHash(
            $token->getPublicHash(),
            $token->getCustomerId()
        );

        if (!empty($tokenDuplicate)) {
            if ($token->getIsVisible() || $tokenDuplicate->getIsVisible()) {
                $this->vaultBackupManagement->backupRemovableVaultGatewayToken($token, $tokenDuplicate);
            } elseif ($token->getIsVisible() === $tokenDuplicate->getIsVisible()) {
                $this->vaultBackupManagement->backupRemovableVaultGatewayToken($token, $tokenDuplicate);
            }
        }

        return [$token, $payment];
    }
}
