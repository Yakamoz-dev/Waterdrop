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
namespace Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\Payment;

use Aheadworks\Sarp2\Model\ThirdPartyModule\Manager;
use Magento\Framework\View\Element\Template;

/**
 * Class Nmi
 *
 * @package Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit\Payment
 */
class Nmi extends Template
{
    /**
     * @var Manager
     */
    private $thirdPartyModuleManager;

    /**
     * @param Template\Context $context
     * @param Manager $thirdPartyModuleManager
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Manager $thirdPartyModuleManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->thirdPartyModuleManager = $thirdPartyModuleManager;
    }

    /**
     * @inheritDoc
     */
    protected function _toHtml()
    {
        $html = '';

        if ($this->thirdPartyModuleManager->isNmiModuleEnabled()) {
            try {
                $block = $this->getLayout()->createBlock(\Aheadworks\Nmi\Block\Checkout\CollectJs::class);
                if ($block) {
                    $html = $block->toHtml();
                }
            } catch (\Exception $exception) {
            }
        }

        return $html;
    }
}
