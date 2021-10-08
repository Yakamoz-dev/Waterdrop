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
namespace Aheadworks\Sarp2\Engine\Payment\Processor\Type\Planned\Bundle;

use Aheadworks\Sarp2\Engine\PaymentInterface;

/**
 * Class Candidate
 * @package Aheadworks\Sarp2\Engine\Payment\Processor\Type\Planned\Bundle
 */
class Candidate
{
    /**
     * @var PaymentInterface
     */
    private $parent;

    /**
     * @var PaymentInterface[]
     */
    private $children;

    /**
     * @param $parent
     * @param array $children
     */
    public function __construct(
        $parent,
        array $children
    ) {
        $this->parent = $parent;
        $this->children = $children;
    }

    /**
     * Get parent payment
     *
     * @return PaymentInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get child payments
     *
     * @return PaymentInterface[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}
