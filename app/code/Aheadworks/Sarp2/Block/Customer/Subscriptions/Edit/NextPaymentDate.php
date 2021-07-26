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
namespace Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit;

use Aheadworks\Sarp2\Model\DateTime\FormatConverter;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Aheadworks\Sarp2\Api\ProfileManagementInterface;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Model\Config;

/**
 * Class NextPaymentDate
 *
 * @package Aheadworks\Sarp2\Block\Customer\Subscriptions\Edit
 */
class NextPaymentDate extends Template
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ProfileManagementInterface
     */
    private $profileManagement;

    /**
     * @var FormatConverter
     */
    private $dateFormatConverter;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ProfileManagementInterface $profileManagement
     * @param Config $config
     * @param FormatConverter $dateFormatConverter
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ProfileManagementInterface $profileManagement,
        Config $config,
        FormatConverter $dateFormatConverter,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->profileManagement = $profileManagement;
        $this->config = $config;
        $this->dateFormatConverter = $dateFormatConverter;
    }

    /**
     * Get next payment date
     *
     * @return string
     * @throws LocalizedException
     */
    public function getNextPaymentDate()
    {
        $profileId = $this->getProfile()->getProfileId();
        $nextPaymentInfo = $this->profileManagement->getNextPaymentInfo($profileId);
        $nextPaymentDate = $nextPaymentInfo->getPaymentDate();
        if ($nextPaymentDate) {
            $nextPaymentDate = new \DateTime($nextPaymentDate);
            $nextPaymentDateAsDateTime = $this->_localeDate->date($nextPaymentDate);
            $nextPaymentDate = $nextPaymentDateAsDateTime->format(
                $this->dateFormatConverter->convertToDateTimeFormat()
            );
        } else {
            $nextPaymentDate = '';
        }

        return $nextPaymentDate;
    }

    /**
     * Retrieve short date format
     *
     * @return string
     */
    public function getJsCalendarDateFormat()
    {
        return $this->dateFormatConverter->convertToJsCalendarFormat();
    }

    /**
     * Retrieve short date format
     *
     * @return string
     */
    public function getMomentJsDateFormat()
    {
        return $this->dateFormatConverter->convertToMomentJsFormat();
    }

    /**
     * Get earliest date
     *
     * @return string
     */
    public function getEarliestDate()
    {
        $earliestDateTime = $this->_localeDate->date();
        $addDays = $this->config->getEarliestNextPaymentDate($this->getProfile()->getStoreId());
        if (is_numeric($addDays)) {
            $earliestDateTime->modify('+' . $addDays . ' day');
        }
        $earliestDateFormatted = $earliestDateTime->format(
            $this->dateFormatConverter->convertToDateTimeFormat()
        );

        return $earliestDateFormatted;
    }

    /**
     * Retrieve save url
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->_urlBuilder->getUrl(
            'aw_sarp2/profile_edit/saveNextPaymentDate',
            ['profile_id' => $this->getProfile()->getProfileId()]
        );
    }

    /**
     * Get profile
     *
     * @return ProfileInterface
     */
    private function getProfile()
    {
        return $this->registry->registry('profile');
    }
}
