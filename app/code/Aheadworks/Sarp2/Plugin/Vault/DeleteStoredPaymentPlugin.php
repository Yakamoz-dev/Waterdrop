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

use Aheadworks\Sarp2\Model\Payment\Token\Processor\UnActivateProcessor as DeleteTokenProcessor;
use Aheadworks\Sarp2\Model\Vault\Data\BackupManagement as VaultBackupManagement;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Framework\UrlInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;

/**
 * Class DeleteStoredPaymentPlugin
 *
 * @package Aheadworks\Sarp2\Plugin\Vault
 */
class DeleteStoredPaymentPlugin
{
    /**
     * @var VaultBackupManagement
     */
    private $vaultBackupManagement;

    /**
     * @var DeleteTokenProcessor
     */
    private $tokenUnActivateProcessor;

    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param VaultBackupManagement $backupManagement
     * @param DeleteTokenProcessor $vaultDeleteProcessor
     * @param MessageManager $messageManager
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        VaultBackupManagement $backupManagement,
        DeleteTokenProcessor $vaultDeleteProcessor,
        MessageManager $messageManager,
        UrlInterface $urlBuilder
    ) {
        $this->vaultBackupManagement = $backupManagement;
        $this->tokenUnActivateProcessor = $vaultDeleteProcessor;
        $this->messageManager = $messageManager;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param \Magento\Vault\Api\PaymentTokenRepositoryInterface $subject
     * @param bool $result
     * @param PaymentTokenInterface $vaultToken
     * @return bool
     */
    public function afterDelete(
        \Magento\Vault\Api\PaymentTokenRepositoryInterface $subject,
        $result,
        PaymentTokenInterface $vaultToken
    ) {
        if ($result) {
            $this->performProcessing($vaultToken);
        }

        return $result;
    }

    /**
     * Perform un activate tokens
     *
     * @param PaymentTokenInterface $vault
     */
    private function performProcessing($vault)
    {
        $gatewayToken = $vault->getGatewayToken();
        $profiles = $this->tokenUnActivateProcessor->unActivateTokenAndSuspendRelatedProfiles($gatewayToken);

        $backupedGatewayTokens = $this->vaultBackupManagement->getBackupedGatewayTokens($vault);
        foreach ($backupedGatewayTokens as $backupGatewayToken) {
            $profiles += $this->tokenUnActivateProcessor->unActivateTokenAndSuspendRelatedProfiles($backupGatewayToken);
        }

        if ($profiles) {
            $this->createWarningMessage();
        }
    }

    /**
     * Create warning message
     */
    private function createWarningMessage()
    {
        $url = $this->urlBuilder->getUrl('aw_sarp2/profile/index');

        $this->messageManager->addComplexWarningMessage(
            'awSarp2DeleteSavedCardWarningMessage',
            ['url' => $url]
        );
    }
}
