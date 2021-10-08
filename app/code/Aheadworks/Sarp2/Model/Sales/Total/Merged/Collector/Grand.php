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
namespace Aheadworks\Sarp2\Model\Sales\Total\Merged\Collector;

use Aheadworks\Sarp2\Model\Sales\Total\Merged\CollectorInterface;
use Aheadworks\Sarp2\Model\Sales\Total\Merged\Collector\Grand\Summator;
use Aheadworks\Sarp2\Model\Sales\Total\Merged\Subject;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Class Grand
 * @package Aheadworks\Sarp2\Model\Sales\Total\Merged\Collector
 */
class Grand implements CollectorInterface
{
    /**
     * @var Summator
     */
    private $grandSummator;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @param Summator $grandSummator
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        Summator $grandSummator,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->grandSummator = $grandSummator;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Subject $subject)
    {
        $baseGrandTotal = $this->grandSummator->getSum();
        $subject->getOrder()
            ->setBaseGrandTotal($baseGrandTotal)
            ->setGrandTotal($this->priceCurrency->convert($baseGrandTotal));
    }
}
