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
namespace Aheadworks\Sarp2\Model\Integration\ModuleAvailability;

use Aheadworks\Sarp2\Model\Integration\IntegratedMethodInterface;
use Magento\Framework\Module\ModuleListInterface;

/**
 * Class Checker
 *
 * @package Aheadworks\Sarp2\Model\Integration\ModuleAvailability
 */
class Checker implements CheckerInterface
{
    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @param ModuleListInterface $moduleList
     */
    public function __construct(ModuleListInterface $moduleList)
    {
        $this->moduleList = $moduleList;
    }

    /**
     * @inheritDoc
     */
    public function check(IntegratedMethodInterface $integrableMethod): bool
    {
        return $this->moduleList->has($integrableMethod->getModuleName());
    }
}
