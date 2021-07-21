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
namespace Aheadworks\Sarp2\Model\Plan\PostDataProcessor;

use Aheadworks\Sarp2\Model\Plan\DataProvider\SameAsRegularMap;

/**
 * Class SameAsRegularCopier
 *
 * @package Aheadworks\Sarp2\Model\Plan\PostDataProcessor
 */
class SameAsRegularCopier implements ProcessorInterface
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
     * @inheritdoc
     */
    public function prepareEntityData($data)
    {
        if (isset($data['trial_same_as_regular']) && (bool)$data['trial_same_as_regular']) {
            foreach ($this->map->get() as $samePair) {
                $data['definition'][$samePair['to']] = $data['definition'][$samePair['from']];
            }
        }

        return $data;
    }
}
