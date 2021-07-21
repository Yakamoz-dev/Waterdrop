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
namespace Aheadworks\Sarp2\Model\Sales\Total\Profile\Collector;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Model\Profile\Item\Options\Extractor as OptionExtractor;
use Aheadworks\Sarp2\Model\Sales\Total\GroupInterface;
use Aheadworks\Sarp2\Model\Sales\Total\PopulatorInterface;
use Aheadworks\Sarp2\Model\Sales\Total\Profile\CollectorInterface;
use Magento\Framework\DataObject\Factory;

/**
 * Class InitialFee
 */
class InitialFee implements CollectorInterface
{
    /**
     * @var GroupInterface
     */
    private $totalsGroup;

    /**
     * @var Factory
     */
    private $dataObjectFactory;

    /**
     * @var OptionExtractor
     */
    private $optionExtractor;

    /**
     * @param GroupInterface $totalsGroup
     * @param Factory $dataObjectFactory
     * @param OptionExtractor $subscriptionOptionExtractor
     */
    public function __construct(
        GroupInterface $totalsGroup,
        Factory $dataObjectFactory,
        OptionExtractor $subscriptionOptionExtractor
    ) {
        $this->totalsGroup = $totalsGroup;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->optionExtractor = $subscriptionOptionExtractor;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(ProfileInterface $profile)
    {
        $baseFeeTotal = 0;
        $currencyOptionConvert = PopulatorInterface::CURRENCY_OPTION_CONVERT;

        if ($profile->getPlanDefinition()->getIsInitialFeeEnabled()) {
            foreach ($profile->getItems() as $item) {
                if (!$item->getParentItem()) {
                    $option = $this->optionExtractor->getSubscriptionOption($item);
                    if ($option) {
                        $baseFee = $option->getInitialFee();
                        $this->totalsGroup->getPopulator(ProfileItemInterface::class)
                            ->populate(
                                $item,
                                $this->dataObjectFactory->create(['fee' => $baseFee]),
                                $currencyOptionConvert
                            );

                        $baseFeeTotal += $baseFee;
                    }
                }
            }

            $this->totalsGroup->getPopulator(ProfileInterface::class)
                ->populate(
                    $profile,
                    $this->dataObjectFactory->create(['fee' => $baseFeeTotal]),
                    $currencyOptionConvert
                );
        }
    }
}
