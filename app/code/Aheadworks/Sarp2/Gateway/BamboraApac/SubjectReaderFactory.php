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
namespace Aheadworks\Sarp2\Gateway\BamboraApac;

use Magento\Framework\ObjectManagerInterface;
use Aheadworks\Sarp2\Model\ThirdPartyModule\Manager as ThirdPartyModuleManager;

/**
 * Class SubjectReaderFactory
 *
 * @package Aheadworks\Sarp2\Gateway\BamboraApac
 */
class SubjectReaderFactory
{
    /**
     * @var ThirdPartyModuleManager
     */
    private $thirdPartyModuleManager;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ThirdPartyModuleManager $thirdPartyModuleManager
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ThirdPartyModuleManager $thirdPartyModuleManager,
        ObjectManagerInterface $objectManager
    ) {
        $this->thirdPartyModuleManager = $thirdPartyModuleManager;
        $this->objectManager = $objectManager;
    }

    /**
     * Get subjectReader object instance
     *
     * @return object|null
     */
    public function getInstance()
    {
        if ($this->thirdPartyModuleManager->isBamboraApacModuleEnabled()) {
            $instance = $this->objectManager->get(\Aheadworks\BamboraApac\Gateway\SubjectReader::class);
        } else {
            $instance = null;
        }

        return $instance;
    }
}
