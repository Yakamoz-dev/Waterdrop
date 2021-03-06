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
namespace Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails;

use Magento\Framework\View\Element\Template;

/**
 * Class AbstractTokenRenderer
 *
 * @package Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\View\PaymentDetails
 */
abstract class AbstractTokenRenderer extends Template implements TokenRendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function render($token)
    {
        $this->assign('token', $token);
        $this->assign('tokenDetails', $token->getDetails());
        return $this->toHtml();
    }
}
