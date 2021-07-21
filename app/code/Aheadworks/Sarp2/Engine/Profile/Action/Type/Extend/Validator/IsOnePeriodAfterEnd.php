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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\Validator;

use Aheadworks\Sarp2\Engine\DataResolver\NextPaymentDate;
use Aheadworks\Sarp2\Model\Profile\Source\Status as StatusSource;
use Magento\Framework\Stdlib\DateTime\DateTime as CoreDate;
use Aheadworks\Sarp2\Engine\Profile\Action\Validation\AbstractValidator;

/**
 * Class StatusValidator
 *
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\Extend\Validator
 */
class IsOnePeriodAfterEnd extends AbstractValidator
{
    /**
     * @var NextPaymentDate
     */
    private $nextPaymentDate;

    /**
     * @var CoreDate
     */
    private $coreDate;

    /**
     * @param NextPaymentDate $nextPaymentDate
     * @param CoreDate $coreDate
     */
    public function __construct(
        NextPaymentDate $nextPaymentDate,
        CoreDate $coreDate
    ) {
        $this->nextPaymentDate = $nextPaymentDate;
        $this->coreDate = $coreDate;
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function performValidation($profile, $action)
    {
        if ($profile->getStatus() == StatusSource::EXPIRED) {
            $nextPaymentDate = $this->nextPaymentDate->getDateNext(
                $profile->getLastOrderDate(),
                $profile->getProfileDefinition()->getBillingPeriod(),
                $profile->getProfileDefinition()->getBillingFrequency()
            );

            $nextPaymentDateTm = $this->coreDate->gmtTimestamp($nextPaymentDate);
            $todayTm = $this->coreDate->gmtTimestamp();

            if ($todayTm > $nextPaymentDateTm) {
                $this->addMessages(['This action is not possible for this subscription.']);
            }
        }
    }
}
