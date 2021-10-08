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
namespace Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Model\Sales\Total\GroupInterface;
use Aheadworks\Sarp2\Model\Sales\Total\PopulatorInterface;
use Aheadworks\Sarp2\Model\Sales\Total\Profile\CollectorInterface;
use Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector\Grand\Summator;
use Magento\Framework\DataObject\Factory;

/**
 * Class Grand
 */
class Grand implements CollectorInterface
{
    /**
     * @var Summator
     */
    private $grandSummator;

    /**
     * @var GroupInterface
     */
    private $totalsGroup;

    /**
     * @var Factory
     */
    private $dataObjectFactory;

    /**
     * @param Summator $grandSummator
     * @param GroupInterface $totalsGroup
     * @param Factory $dataObjectFactory
     */
    public function __construct(
        Summator $grandSummator,
        GroupInterface $totalsGroup,
        Factory $dataObjectFactory
    ) {
        $this->grandSummator = $grandSummator;
        $this->totalsGroup = $totalsGroup;
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(ProfileInterface $profile)
    {
        $baseGrandTotal = $this->grandSummator->getSum($this->totalsGroup->getCode());
        $this->totalsGroup->getPopulator(ProfileInterface::class)
            ->populate(
                $profile,
                $this->dataObjectFactory->create(['grand_total' => $baseGrandTotal]),
                PopulatorInterface::CURRENCY_OPTION_CONVERT,
                $profile->getProfileCurrencyCode()
            );
    }
}
