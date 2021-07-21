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
namespace Aheadworks\Sarp2\Model\DateTime;

use Magento\Framework\Stdlib\DateTime;

/**
 * Class Modifier
 * @package Aheadworks\Sarp2\Model\DateTime
 */
class Modifier
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @param DateTime $dateTime
     */
    public function __construct(
        DateTime $dateTime
    ) {
        $this->dateTime = $dateTime;
    }

    /**
     * Copy time
     *
     * @param string $source
     * @param string $destination
     * @return string
     */
    public function copyTime($source, $destination)
    {
        $sourceDateTime = new \DateTime($source);
        $destinationDateTime = new \DateTime($destination);

        $timeArray = explode(':', $sourceDateTime->format('H:i:s'));
        $destinationDateTime->setTime($timeArray[0], $timeArray[1], $timeArray[2]);

        return $this->dateTime->formatDate($destinationDateTime);
    }
}
