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
namespace Aheadworks\Sarp2\Engine\Profile\Action\Type\AddItemsFromQuoteToNearest\Validator;

use Aheadworks\Sarp2\Api\Data\ScheduledPaymentInfoInterface;
use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Aheadworks\Sarp2\Engine\Profile\Action\Validation\AbstractValidator;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class NextPaymentStatus
 * @package Aheadworks\Sarp2\Engine\Profile\Action\Type\AddItemsFromQuoteToNearest\Validator
 */
class NextPaymentStatus extends AbstractValidator
{
    /**
     * @var ProfileManagementInterface
     */
    private $profileManagement;

    /**
     * @param ProfileManagementInterface $profileManagement
     */
    public function __construct(
        ProfileManagementInterface $profileManagement
    ) {
        $this->profileManagement = $profileManagement;
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws LocalizedException
     */
    protected function performValidation($profile, $action)
    {
        $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profile->getProfileId());

        if ($nextPaymentInfo->getPaymentStatus() == ScheduledPaymentInfoInterface::PAYMENT_STATUS_LAST_PERIOD_HOLDER) {
            $this->addMessages(['This action is not allowed for membership subscription.']);
        }
    }
}
