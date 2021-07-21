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
namespace Aheadworks\Sarp2\Cron;

use Aheadworks\Sarp2\Engine\Notification\Offer\Extend\Processor as ExtendSubscriptionProcessor;

/**
 * Class ProcessOffers
 *
 * @package Aheadworks\Sarp2\Cron
 */
class ProcessOffers
{
    /**
     * @var ExtendSubscriptionProcessor
     */
    private $extendSubscriptionProcessor;

    /**
     * @param ExtendSubscriptionProcessor $processor
     */
    public function __construct(ExtendSubscriptionProcessor $processor)
    {
        $this->extendSubscriptionProcessor = $processor;
    }

    /**
     * Perform processing of offers
     *
     * @return void
     */
    public function execute()
    {
        $this->extendSubscriptionProcessor->processProfileWithOfferForToday();
    }
}
