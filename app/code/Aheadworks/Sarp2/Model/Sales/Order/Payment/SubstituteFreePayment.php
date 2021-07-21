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
namespace Aheadworks\Sarp2\Model\Sales\Order\Payment;

/**
 * Class SubstituteFreePayment
 *
 * @package Aheadworks\Sarp2\Model\Sales\Order\Payment
 */
class SubstituteFreePayment extends \Magento\Payment\Model\Method\Free
{
    /**
     * @var string
     */
    private $title;

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     */
    public function getConfigData($field, $storeId = null)
    {
        if ('title' == $field) {
            return $this->getTitle();
        }

        return parent::getConfigData($field, $storeId);
    }

    /**
     * Set method title
     *
     * @param string $title
     * @return SubstituteFreePayment
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }
}
