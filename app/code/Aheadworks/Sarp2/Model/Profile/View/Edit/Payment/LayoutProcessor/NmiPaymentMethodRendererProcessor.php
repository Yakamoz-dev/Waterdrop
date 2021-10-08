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
namespace Aheadworks\Sarp2\Model\Profile\View\Edit\Payment\LayoutProcessor;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\Stdlib\ArrayManager;

/**
 * Class NmiPaymentMethodRendererProcessor
 *
 * @package Aheadworks\Sarp2\Model\Profile\View\Edit\Payment\LayoutProcessor
 */
class NmiPaymentMethodRendererProcessor implements LayoutProcessorInterface
{
    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        ArrayManager $arrayManager
    ) {
        $this->arrayManager = $arrayManager;
    }

    /**
     * @inheritdoc
     */
    public function process($jsLayout)
    {
        $path = 'components/payment/children/renders/children/aw_nmi/component';

        $component = $this->arrayManager->get($path, $jsLayout);
        if ($component) {
            $jsLayout = $this->arrayManager->set(
                $path,
                $jsLayout,
                'Aheadworks_Sarp2/js/checkout/view/payment-method/renderer/nmi/aw-nmi'
            );
        }

        return $jsLayout;
    }
}
