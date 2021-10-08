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
namespace Aheadworks\Sarp2\Block\Product\SubscriptionOptions\Renderer;

use Aheadworks\Sarp2\Block\Product\SubscriptionOptions;

/**
 * Class Dropdown
 *
 * @method SubscriptionOptions getRenderedBlock()
 * @package Aheadworks\Sarp2\Block\Product\SubscriptionOptions
 */
class Dropdown extends AbstractRenderer
{
    /**
     * {@inheritdoc}
     */
    protected $_template = 'product/subscription_options/renderers/dropdown.phtml';

    /**
     * Check if need show No Plan radiobutton
     *
     * @return bool
     */
    public function isShowNoPlanRadiobutton()
    {
        return $this->getRenderedBlock()->isFirstOptionNoPlan();
    }

    /**
     * @inheritDoc
     */
    public function getChangeEvent()
    {
        return 'change select';
    }
}
