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
namespace Aheadworks\Sarp2\Model\Product\Attribute\Backend\SubscriptionOptions;

use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;

/**
 * Class Dummy
 * @package Aheadworks\Sarp2\Model\Product\Attribute\Backend\SubscriptionOptions
 */
class Dummy extends AbstractBackend
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate($object)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isScalar()
    {
        return false;
    }
}
