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
namespace Aheadworks\Sarp2\Engine\Profile;

use Magento\Framework\DataObject\Factory;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class ActionFactory
 * @package Aheadworks\Sarp2\Engine\Profile
 */
class ActionFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Factory
     */
    private $dataObjectFactory;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param Factory $dataObjectFactory
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        Factory $dataObjectFactory
    ) {
        $this->objectManager = $objectManager;
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * Create profile action instance
     *
     * @param array $data
     * @return Action
     */
    public function create(array $data)
    {
        $data['data'] = $this->dataObjectFactory->create(
            isset($data['data'])
                ? $data['data']
                : []
        );
        return $this->objectManager->create(Action::class, $data);
    }
}
