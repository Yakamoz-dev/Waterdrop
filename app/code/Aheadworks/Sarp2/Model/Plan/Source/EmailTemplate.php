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
namespace Aheadworks\Sarp2\Model\Plan\Source;

use Magento\Config\Model\Config\Source\Email\Template;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class EmailTemplate
 *
 * @package Aheadworks\Sarp2\Model\Plan\Source
 */
class EmailTemplate implements ArrayInterface
{
    /**
     * @var Template
     */
    private $template;

    /**
     * @param Template $template
     */
    public function __construct(
        Template $template
    ) {
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->template
            ->setPath('aw_sarp2_email_settings_offer_extend_subscription_email_template')
            ->toOptionArray();
    }
}
