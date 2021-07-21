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
namespace Aheadworks\Sarp2\Plugin\Framework\Data\Form\Element;

use Magento\Framework\Data\Form\Element\Fieldset;

/**
 * Class FieldsetPlugin
 * @package Aheadworks\Sarp2\Plugin\Framework\Data\Form\Element
 */
class FieldsetPlugin
{
    /**
     * Add field to fieldset
     *
     * @param Fieldset $subject
     * @param string $elementId
     * @param string $type
     * @param array $config
     * @param bool $after
     * @param bool $isAdvanced
     * @return array
     */
    public function beforeAddField($subject, $elementId, $type, $config, $after = false, $isAdvanced = false)
    {
        if ($elementId == 'aw_sarp2_subscription_options') {
            $type = 'text_aw_sarp2_subscription_options';
        }
        return [$elementId, $type, $config, $after, $isAdvanced];
    }
}
