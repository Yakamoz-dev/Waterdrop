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
namespace Aheadworks\Sarp2\PaymentData\Create;

use Magento\Framework\DataObject\Factory;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class ResultFactory
 * @package Aheadworks\Sarp2\PaymentData\Create
 */
class ResultFactory
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
     * Create result instance
     *
     * @param array $data
     * @return Result
     */
    public function create(array $data)
    {
        if (isset($data['additionalData'])) {
            $data['additionalData'] = is_array($data['additionalData'])
                ? $this->dataObjectFactory->create($data['additionalData'])
                : null;
        }
        return $this->objectManager->create(Result::class, $data);
    }
}
