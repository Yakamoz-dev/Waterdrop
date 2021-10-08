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
 * @package    Sarp2Stripe
 * @version    1.0.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Sarp2Stripe\Plugin\Controller;

use Magento\Framework\App\ResponseInterface;
use Aheadworks\Sarp2\Plugin\Vault\DeleteStoredPaymentPlugin as Sarp2DeleteStoredPaymentPlugin;

class DeleteStoredPaymentPlugin extends Sarp2DeleteStoredPaymentPlugin
{
    /**
     * @param \StripeIntegration\Payments\Controller\Customer\Cards $subject
     * @param ResponseInterface $result
     * @param string $stripeToken
     * @return ResponseInterface
     */
    public function afterDeleteCard($subject, $result, $stripeToken)
    {
        $this->performProcessingByVaultTokenValue($stripeToken);

        return $result;
    }
}
