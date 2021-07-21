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
namespace Aheadworks\Sarp2\Model\Email;

/**
 * Class Template
 *
 * @package Aheadworks\Sarp2\Model\Email
 */
class Template extends \Magento\Email\Model\Template
{
    /**
     * @inheritDoc
     */
    public function load($modelId, $field = null)
    {
        parent::load($modelId, $field);
        $this->setData('is_legacy', true);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function loadDefault($templateId)
    {
        parent::loadDefault($templateId);
        $this->setData('is_legacy', true);

        return $this;
    }
}
