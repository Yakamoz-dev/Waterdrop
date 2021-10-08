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
namespace Aheadworks\Sarp2\Model\Plan\DataProvider\Processor;

use Aheadworks\Sarp2\Api\Data\PlanInterface;
use Aheadworks\Sarp2\Model\Plan\DataProvider\SameAsRegularMap;

/**
 * Class SameAsRegularInit
 *
 * @package Aheadworks\Sarp2\Model\Plan\DataProvider\Processor
 */
class SameAsRegularInit implements ProcessorInterface
{
    /**
     * @var SameAsRegularMap
     */
    private $map;

    /**
     * @param SameAsRegularMap $map
     */
    public function __construct(SameAsRegularMap $map)
    {
        $this->map = $map;
    }

    /**
     * @inheritDoc
     */
    public function process($data)
    {
        if (!isset($data[PlanInterface::PLAN_ID])) {
            return $data;
        }

        $isSame = true;

        foreach ($this->map->get() as $samePair) {
            if ($data['definition'][$samePair['to']] != $data['definition'][$samePair['from']]) {
                $isSame = false;
            }
        }

        $data['trial_same_as_regular'] = (string)(int)$isSame;

        return $data;
    }
}
