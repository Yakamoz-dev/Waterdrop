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
namespace Aheadworks\Sarp2\Model\Profile\Item\Options;

use Aheadworks\Sarp2\Api\Data\ProfileItemInterface;
use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface;
use Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterfaceFactory;
use Aheadworks\Sarp2\Api\SubscriptionOptionRepositoryInterface;
use Aheadworks\Sarp2\Model\Profile\Item;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Class Extractor
 */
class Extractor
{
    /**
     * @var SubscriptionOptionInterfaceFactory
     */
    private $subscriptionOptionFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var SubscriptionOptionRepositoryInterface
     */
    private $optionRepository;

    /**
     * Extractor constructor.
     *
     * @param SubscriptionOptionRepositoryInterface $optionRepository
     * @param SubscriptionOptionInterfaceFactory $subscriptionOptionFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        SubscriptionOptionRepositoryInterface $optionRepository,
        SubscriptionOptionInterfaceFactory $subscriptionOptionFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->optionRepository = $optionRepository;
        $this->subscriptionOptionFactory = $subscriptionOptionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Retrieve item subscription option
     *
     * @param ProfileItemInterface|Item $item
     * @return \Aheadworks\Sarp2\Api\Data\SubscriptionOptionInterface|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSubscriptionOption($item)
    {
        $option = null;
        $productOptions = $item->getProductOptions();
        if (isset($productOptions['info_buyRequest']['aw_sarp2_subscription_option'])) {
            $option = $this->subscriptionOptionFactory->create();
            $optionArray = $productOptions['info_buyRequest']['aw_sarp2_subscription_option'];
            $this->dataObjectHelper->populateWithArray($option, $optionArray, SubscriptionOptionInterface::class);
        } elseif (isset($productOptions['aw_sarp2_subscription_option']['option_id'])) {
            $optionId = $productOptions['aw_sarp2_subscription_option']['option_id'];
            if ($optionId) {
                $option = $this->optionRepository->get($optionId);
            }
        } else {
            $optionId = isset($productOptions['info_buyRequest']['aw_sarp2_subscription_type'])
                ? $productOptions['info_buyRequest']['aw_sarp2_subscription_type']
                : null;
            if ($optionId) {
                $option = $this->optionRepository->get($optionId);
            } else {
                return null;
            }
        }

        return $option;
    }
}
