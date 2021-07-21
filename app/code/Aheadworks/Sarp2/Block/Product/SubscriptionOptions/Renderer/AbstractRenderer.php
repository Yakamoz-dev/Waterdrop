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
namespace Aheadworks\Sarp2\Block\Product\SubscriptionOptions\Renderer;

use Aheadworks\Sarp2\Block\Product\SubscriptionOptions;
use Magento\Framework\View\Element\Template;

/**
 * Class AbstractRenderer
 *
 * @method SubscriptionOptions getRenderedBlock()
 * @package Aheadworks\Sarp2\Block\Product\SubscriptionOptions\Renderer
 */
abstract class AbstractRenderer extends Template
{
    /**
     * Retrieve change event string
     *
     * @return string
     */
    abstract public function getChangeEvent();
}
