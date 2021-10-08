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
namespace Aheadworks\Sarp2\Model\Integration;

/**
 * Class IntegratedMethodList
 *
 * @package Aheadworks\Sarp2\Model\Integration
 */
class IntegratedMethodList
{
    /**
     * @var IntegratedMethodInterface[]
     */
    private $methodList;

    /**
     * @param array $methodList
     */
    public function __construct(
        array $methodList = []
    ) {
        $this->methodList = $methodList;
    }

    /**
     * Get integrable method facade
     *
     * @param string $methodCode
     * @return IntegratedMethodInterface
     * @throws \Exception
     */
    public function getMethod($methodCode)
    {
        foreach ($this->methodList as $integratedMethod) {
            if ($integratedMethod->getCode() == $methodCode) {
                return $integratedMethod;
            }
        }

        throw new \Exception(sprintf('Unknown integrated method facade requested: %s ', $methodCode));
    }

    /**
     * Get integrable method list
     *
     * @return IntegratedMethodInterface[]
     */
    public function getList()
    {
        return $this->methodList;
    }
}
