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
namespace Aheadworks\Sarp2\Model\Sales\Total\Merged;

use Magento\Framework\ObjectManagerInterface;

/**
 * Class CollectorList
 * @package Aheadworks\Sarp2\Model\Sales\Total\Merged
 */
class CollectorList
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var array
     */
    private $collectors;

    /**
     * @var CollectorInterface[]
     */
    private $collectorsInstances;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param array $collectors
     */
    public function __construct(ObjectManagerInterface $objectManager, $collectors = [])
    {
        $this->objectManager = $objectManager;
        $this->collectors = $collectors;
    }

    /**
     * Retrieve collector instances
     *
     * @return CollectorInterface[]
     * @throws \Exception
     */
    public function getCollectors()
    {
        if (!$this->collectorsInstances) {
            $collectors = $this->collectors;
            $this->collectorsInstances = [];
            uasort($collectors, [$this, 'sortCollectors']);
            foreach ($collectors as $code => $collectorDefinition) {
                $collectorInstance = $this->objectManager->create($collectorDefinition['className']);
                if (!$collectorInstance instanceof CollectorInterface) {
                    throw new \Exception(
                        sprintf('Collector instance %s does not implement required interface.', $code)
                    );
                }
                $this->collectorsInstances[$code] = $collectorInstance;
            }
        }
        return $this->collectorsInstances;
    }

    /**
     * Sort total collectors definitions
     *
     * @param array $collectorDefinition1
     * @param array $collectorDefinition2
     * @return int
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function sortCollectors($collectorDefinition1, $collectorDefinition2)
    {
        if (!isset($collectorDefinition1['sortOrder']) || !isset($collectorDefinition2['sortOrder'])) {
            return 0;
        }
        if ($collectorDefinition1['sortOrder'] == $collectorDefinition2['sortOrder']) {
            return 0;
        }
        return $collectorDefinition1['sortOrder'] > $collectorDefinition2['sortOrder'] ? 1 : -1;
    }
}
