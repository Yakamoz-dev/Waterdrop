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
namespace Aheadworks\Sarp2\Engine\Profile\Action;

use Aheadworks\Sarp2\Api\Data\ProfileInterface;
use Aheadworks\Sarp2\Engine\Profile\Action\Type\ChangeStatus\Detector as ChangeStatus;

/**
 * Class CompositeDetector
 * @package Aheadworks\Sarp2\Engine\Profile\Action
 */
class CompositeDetector implements DetectorInterface
{
    /**
     * @var array
     */
    private $detectors = [
        ChangeStatus::class
    ];

    /**
     * @var DetectorInterface[]
     */
    private $detectorList;

    /**
     * @var DetectorFactory
     */
    private $detectorFactory;

    /**
     * @param DetectorFactory $detectorFactory
     * @param array $detectors
     */
    public function __construct(
        DetectorFactory $detectorFactory,
        $detectors = []
    ) {
        $this->detectorFactory = $detectorFactory;
        $this->detectors = array_merge($this->detectors, $detectors);
    }

    /**
     * {@inheritdoc}
     */
    public function detect(ProfileInterface $profile)
    {
        foreach ($this->getDetectorList() as $detector) {
            $action = $detector->detect($profile);
            if ($action) {
                return $action;
            }
        }
        return null;
    }

    /**
     * Get action detectors list
     *
     * @return DetectorInterface[]
     */
    private function getDetectorList()
    {
        if (!$this->detectorList) {
            $this->detectorList = [];
            foreach ($this->detectors as $detector) {
                $this->detectorList[] = $this->detectorFactory->create($detector);
            }
        }
        return $this->detectorList;
    }
}
